kohana-helpers
==============

Helpers classes for Kohana 3.3.1:

- `Helpers_Arr` extends `Kohana_Helpers_Arr` extends `Arr`
- `Helpers_Cache` extends `Kohana_Helpers_Cache`
- `Helpers_CLI` extends `Kohana_Helpers_CLI`
- `Helpers_Core` extends `Kohana_Helpers_Core`
- `Helpers_DB` extends `Kohana_Helpers_DB`
- `Helpers_Exception` extends `Kohana_Helpers_Exception` implements `JsonSerializable`
- `Helpers_File` extends `Kohana_Helpers_File`
- `Helpers_Request` extends `Kohana_Helpers_Request`
- `Helpers_Response` extends `Kohana_Helpers_Response`
- `Helpers_Text` extends `Kohana_Helpers_Text` extends `Text`

---

## Changelog
- **v.0.1.9.4** (2014-03-11):
	- added:
		- function `Helpers_Arr::implode($glue, $pieces)`
		- function `Helpers_Exception::toArray(Exception $e = NULL, array $config = NULL)`
		- function `Helpers_Exception->jsonSerialize()` using `Helpers_Exception::toArray`
		- function `Helpers_Text::trim($value, $charlist = NULL, $utf8 = TRUE, $nullable = FALSE)`
		- [`messages/ru`](./messages/ru.php):
			- added `exception.undefined`
	- changed:
		- function `Helpers_Arr::exception` now **depricated** and will be **removed** in v.0.2
		- class `Helpers_Cache`:
			- removed protected function `Kohana_Helpers_Cache::log`
			- added protected function `Kohana_Helpers_Cache::exception`
		- class `Helpers_Exception`:
			- default message `exception.undefined`
			- casting variables `:text`, `:code`
		- class `Exception_Request`:
			- default message `exception.request`
		- function `Helpers_Text::trimAsNULL` now uses `Helpers_Text::trim`
		- [`config`](./config/helpers.php):
			- added section `exception`
			- changed section `cache` structure

- **v.0.1.9.3** (2014-03-06):
	- changed:
		- function `Helpers_Request::getQuery` fixed type casting

- **v.0.1.9.2** (2014-03-06):
	- changed:
		- class `Helpers_Request` added missing static statements

- **v.0.1.9.1** (2014-03-06):
	- changed:
		- class `Helpers_Arr::merge` strict standarts fix

- **v.0.1.9** (2014-03-06):
	- removed:
		- abstract class `Kohana_Helpers`
	- added:
		- trait `Kohana_HelpersConfig`
		- class `Helpers_Exception` extends `Kohana_Helpers_Exception`
		- class `Helpers_Request` extends `Kohana_Helpers_Request`
		- class `Exception_Request` extends `Kohana_Exception_Request`
		- class `Exception_Request_InvalidJSON` extends `Kohana_Exception_Request_InvalidJSON`
		- function `Helpers_DB::transaction(Closure $closure, $name = NULL, $mode = NULL)`
	- changed:
		- classes
			`Helpers_Cache`,
			`Helpers_CLI`,
			`Helpers_Request`
		  using trait `Kohana_HelpersConfig`
		- class `Helpers_Arr` extends `Arr`
		- [`composer.json`](./composer.json):
			- added `require-dev` section
		- [`config`](./config/helpers.php):
			- added `request` section
