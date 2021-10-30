# Files 

Files browser package


[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/Files/)
[![codecov](https://codecov.io/gh/EscolaLMS/Files/branch/main/graph/badge.svg?token=NRAN4R8AGZ)](https://codecov.io/gh/EscolaLMS/Files)
[![phpunit](https://github.com/EscolaLMS/Files/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Files/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/files)](https://packagist.org/packages/escolalms/files)
[![downloads](https://img.shields.io/packagist/v/escolalms/files)](https://packagist.org/packages/escolalms/files)
[![downloads](https://img.shields.io/packagist/l/escolalms/files)](https://packagist.org/packages/escolalms/files)
[![Maintainability](https://api.codeclimate.com/v1/badges/99e3f317974d77113a6a/maintainability)](https://codeclimate.com/github/EscolaLMS/Files/maintainability)

Reusage files API 

## Installation
In case running as a standalone package you need to insert swagger annotation for documentation to work.
To achieve that, create `src/oa_version.php` file with the following contents:
```php
/**
 * @OA\Info(title="Files API", version="1.0.0-dev")
 */
 ```
In case your developed version is different, replace `1.0.0-dev` with whichever version tag you're working on.
