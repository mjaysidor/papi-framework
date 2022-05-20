## Overview:
The core of any Papi application is the papi.php file. It runs the app and runs any needed peripherals, such as DotEnv component, DocGenerator component, the ControllerInitalizer, etc.. DotEnv imports environment variables from specified .env files, DocGenerator generates OpenAPI documentation for resource endpoints, and ControllerInitalizer initializes endpoints specified in any app controllers.

To run the app use **"php papi.php start"** command.

```
$api = new App();
DotEnv::load('.env.local');
DotEnv::load();
(new ControllerInitializer)->init($api);
DocGenerator::generateOpenAPIDocs(ProjectStructure::getOpenApiDocPath(), $api->getRoutes());

$api->start();
```

### Commands (Workerman):
* **php papi.php start** - Start app in DEBUG mode. Use -d flag to start in background (production environments)
* **php papi.php stop** - Stop app. Use -g flag to stop gracefully.
* **php papi.php restart** - Restart workers. -d & -g flags available.
* **php papi.php reload** - Reload code. -g flag available.
* **php papi.php status** - Get app status. Use -d flag to show live status.
* **php papi.php connections** - Get app connections.


