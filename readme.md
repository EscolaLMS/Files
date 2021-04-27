# Installation
In case running as a standalone package you need to insert swagger annotation for documentation to work.
To achieve that, create `src/oa_version.php` file with the following contents:
```php
/**
 * @OA\Info(title="Files API", version="1.0.0-dev")
 */
 ```
In case your developed version is different, replace `1.0.0-dev` with whichever version tag you're working on.
