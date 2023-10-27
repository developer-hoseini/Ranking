<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Get the path of the resources directory
        $resourcesPath = app_path('Filament/Resources');

        // Get all the files in the resources directory
        // Loop through each file
        foreach (File::files($resourcesPath) as $file) {
            // Get the resource name from the file name
            $resourceName = Str::before($file->getFilename(), '.php');
            $resourceName = Str::replace('Resource', '', $resourceName);

            // Get the policy name from the resource name
            $policyName = $resourceName.'Policy';

            // Get the policy class name with namespace
            $policyClass = 'App\\Policies\\'.$policyName;

            // Check if the policy class exists
            if (! class_exists($policyClass)) {
                // Create the policy file path
                $policyPath = app_path('Policies/'.$policyName.'.php');

                // Create the policy file content
                $policyContent = <<<EOT
<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class {$policyName}
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
EOT;

                // Write the policy file content to the policy file path
                File::put($policyPath, $policyContent);

                // Print a message to indicate the policy creation
                echo "Created {$policyName} for {$resourceName}\n";
            }
        }
    }
}
