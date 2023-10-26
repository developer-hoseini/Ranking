<?php

namespace App\Filament\Resources;

use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->collection('avatar')
                    ->image()
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->openable()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('roles')
                    ->required()
                    ->multiple()
                    ->relationship(name: 'roles', titleAttribute: 'name'),
                TextInput::make('password')
                    ->confirmed()
                    ->password()
                    ->required(fn ($record) => is_null($record))
                    ->maxLength(255),
                TextInput::make('password_confirmation')
                    ->dehydrated()
                    ->required(fn ($record) => is_null($record))
                    ->maxLength(255),
                Toggle::make('active')->visible(fn ($record) => $record->id !== auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatar')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('username')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('active')
                    ->boolean()
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('active'),
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                Action::make('profile')
                    ->icon('heroicon-o-user')
                    ->fillForm(fn (User $record): array => [
                        'mobile' => '99',
                        'show_mobile' => true,
                        ...$record?->profile?->toArray() ?? [],
                        'country' => $record->profile?->state?->country_id,
                    ])
                    ->steps([
                        Step::make('Information User')
                            ->schema([
                                TextInput::make('fname')
                                    ->label('First Name')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('lname')
                                    ->label('Last Name')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('en_fullname')
                                    ->label('Full name (In English)')
                                    ->maxLength(50),
                                TextInput::make('mobile')
                                    ->default('00')
                                    ->label('Mobile')
                                    ->maxLength(30),
                                TextInput::make('code_melli')
                                    ->label('National code')
                                    ->maxLength(50),
                                Toggle::make('show_mobile')
                                    ->label('Show mobile number for opponent?')
                                    ->default(true),
                                DatePicker::make('birth_date')
                                    ->label('Birth Date')
                                    ->before(today()),
                                Select::make('gender')
                                    ->required()
                                    ->options([
                                        1 => 'Male',
                                        0 => 'FeMale',
                                    ]),
                            ])
                            ->columns(2),
                        Step::make('Extra Details')
                            ->schema([
                                Select::make('country')
                                    ->required()
                                    ->options(Country::query()->pluck('name', 'id'))
                                    ->live(),
                                Select::make('state_id')
                                    ->required()
                                    ->label('State')
                                    ->options(fn (Get $get): Collection => State::query()
                                        ->where('country_id', $get('country'))->pluck('name', 'id')
                                    ),
                                TextInput::make('bio')
                                    ->label('Bio')
                                    ->maxLength(100),
                            ]),
                        Step::make('Bank Account')
                            ->schema([
                                TextInput::make('bank_account')
                                    ->label('Bank account')
                                    ->maxLength(100),
                                TextInput::make('account_holder_name')
                                    ->label('Name of account holder')
                                    ->maxLength(100),
                            ]),
                    ])->action(function (array $data, User $record): void {
                        unset($data['country']);
                        if ($record->profile()->updateOrCreate([], $data)) {
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Something Went Wrong')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->groups([
                'active',
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit' => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'users';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function canDelete($user): bool
    {
        return $user->id !== auth()->id();
    }
}
