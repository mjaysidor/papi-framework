## Overview:
Papi comes with a DotEnv component. It loads specified .env files on application startup, so that they can be accessed via getenv($variableName) method anywhere in the app. Loading .env files is best configured inside papi.php file. To load variables from file simply call DotEnv::load($fileName) method.

### Examples:
```
// create app instance
$api = new App();
// load file with specified name
DotEnv::load('.env.local');
// load default file - '.env' 
DotEnv::load();

// run app
$api->start();
```