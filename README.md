Affinite WP plugin update server
=======

Requirements
-----------

- PHP >= 8.0

Installation
-----------

1. Copy all files to your destination
2. Open `src/Server.php`
3. Edit `SERVER_HOST` and `LOG_DIR`
4. **optional**: If you download source code you need to install dependencies with composer. Run `composer install` to install dependencies
5. **optional**: If you need validate license do not forget to edit `LICENSE_HOST` (also `LICENSE_HTTP_USER` and `LICENSE_HTTP_PASSWORD` for auth) in `src/Server.php`
6. Done!

Usage
-----------

1. Create folder in `/plugins` named as your plugin folder (eg. affinite-plugin)
2. Create `plugin.json` file in your plugin directory and fill informations about plugin
3. Create version directory (replace `.` to `-`, eg. `1.0.0` to `1-0-0`) in your plugin directory and place your plugin `.zip` file here
4. **Optional:** Create `banners` folder in your plugin folder and add `low.jpg` and `high.jpg` banners

For every new plugin version follow point number `3` and update `version` in `plugin.json`.

### Sample

- `/logs` **(optional, depends on `/src/Server.php` -> `LOG_DIR`)**
- `/plugins`
    * `/affinite-plugin`
        * `/1-0-0`
          * `affinite-plugin.zip`
        * `/banners`
          * `low.jpg`
          * `high.jpg`
        * `plugin.json`
- `/src` **(do not touch)**
- `/sample` **(sample plugin folder)**
- `index.php` **(do not touch)**

Usage in your plugin
-----------

Depends on your settings in `src/Server.php` you can send `GET` request to `SERVER_HOST/?plugin=<plugin_slug>`.

### Parameters
- `plugin` **(required)**
- `version` **(optional)**
- `download` **(optional)** with value `1` to force download plugin `.zip` file. If `version` is specified then this version will be downloaded.
- `license` **(required)** if `license` parameter is set to `true` in `plugin.json`

Example: `SERVER_HOST/?plugin=<plugin_slug>&version=1.0.0&download=1&license=abcd123`

## License

[MIT](LICENSE)
