# Admin Notice Helper
An easy and convenient way for WordPress plugins and themes to display messages and errors to the user within the Administration Panels.

## Usage

Include the class from your plugin's main file or theme's functions.php file. For example:

```php
require( __DIR__ . '/includes/admin-notice-helper/admin-notice-helper.php' );
```

Call the `add_notice()` function anywhere in your code after WordPress fires the 'init' hook. If you call it before the init hook fires, it won't work. If you want to call it during a callback to the init hook, make sure the callback is registered with a priority of 10 or higher. (The default priority is 10, so you only need to worry about this is you manually register at a lower priority.)

```php
function my_example() {
	if( $success ) {
		add_notice( 'Successful' );
	} else {
		add_notice( 'Failure', 'error' );
	}
}
add_action( 'save_posts', 'my_example' );
```