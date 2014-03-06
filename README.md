kohana-helpers
==============

Helpers classes for Kohana 3.3.1:

- `Helpers_Arr` extends `Arr`
- `Helpers_Cache`
- `Helpers_CLI`
- `Helpers_Core`
- `Helpers_DB`
- `Helpers_Exception`
- `Helpers_File`
- `Helpers_Request`
- `Helpers_Response`
- `Helpers_Text` extends `Text`

---

## Changelog
- **v.0.1.9.1** (2014-03-06):
	- changed:
		- `Kohana_Helpers_Arr::merge` strict standarts fix

- **v.0.1.9** (2014-03-06):
	- removed:
		- abstract class `Kohana_Helpers`
	- added:
		- trait `Kohana_HelpersConfig`
		- class `Helpers_Exception` extends `Kohana_Helpers_Exception`
		- class `Helpers_Request` extends `Kohana_Helpers_Request`
		- class `Exception_Request` extends `Kohana_Exception_Request`
		- class `Exception_Request_InvalidJSON` extends `Kohana_Exception_Request_InvalidJSON`
		- `Helpers_DB::transaction(Closure $closure, $name = NULL, $mode = NULL)`
	- changed:
		- classes
			`Kohana_Helpers_Cache`,
			`Kohana_Helpers_CLI`,
			`Kohana_Helpers_Request`
		  using trait `Kohana_HelpersConfig`
		- class `Kohana_Helpers_Arr` extends `Arr`
		- `composer.json`:
			- added `require-dev` section
		- `config/helpers.php`:
			- added `request` section
