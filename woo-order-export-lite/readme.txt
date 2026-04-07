=== Advanced Order Export For WooCommerce ===
Contributors: algolplus
Donate link: 
Tags: order export,export orders,woocommerce,order,export
Requires PHP: 7.4.0
Requires at least: 4.7
Tested up to: 6.9
Stable tag: 4.0.7
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Export WooCommerce orders to Excel, CSV, XML, JSON, PDF and HTML. Best free order export plugin for WooCommerce. 

== Description ==
This plugin helps you to **easily** export WooCommerce order data. 

Export any custom field assigned to orders/products/coupons is easy and you can select from various formats to export the data in such as Excel, CSV, XML, JSON, PDF and HTML.

= Features =

* **select** [the fields to export](https://docs.algolplus.com/algol_order_export/export-now/set-up-fields-to-export/)
* **rename** labels 
* **reorder** columns 
* export **custom fields** or terms for products/orders
*  **group data** [by products or customers](https://docs.algolplus.com/algol_order_export/export-now/overview-12/#summary-reports)
* export orders via  **bulk action** from orders list 
* apply **powerful filters** and much more

= Export includes =

* order data
* summary order details (# of items, discounts, taxes etc…)
* customer details (both shipping and billing)
* product attributes
* coupon details
* order item metadata, etc.

= Use this plugin to export orders for =

* sending order data to 3rd part drop shippers
* updating your accounting system
* analysing your order data

= Pro version  =

Are you looking to have your WooCommerce products drop shipped from a third party? Our plugin can help you export your orders to CSV/XML/etc and send them to your drop shipper. You can even automate this process with [Pro version](https://algolplus.com/plugins/downloads/advanced-order-export-for-woocommerce-pro/) .

= Pro version features  =
* Export a single order immediately [after a status change](https://docs.algolplus.com/algol_order_export/pro-version-algol_order_export/status-change-jobs/) (e.g., after payment)
* Export orders on [a flexible schedule](https://docs.algolplus.com/algol_order_export/pro-version-algol_order_export/scheduled-jobs/schedule/)
* Exported orders can be sent to [multiple locations at once](https://docs.algolplus.com/algol_order_export/pro-version-algol_order_export/destination-block/overview-14/) - via email, FTP, and various APIs
* Updating Google Sheets/Drive is also possible, but this requires [Zapier service](https://docs.algolplus.com/algol_order_export/pro-version-algol_order_export/destination-block/zapier/adding-a-zapier-account/)

For complete list of features -  please visit [Advanced Order Export For WooCommerce](https://algolplus.com/plugins/downloads/advanced-order-export-for-woocommerce-pro/)

= Compatibility  =
The plugin is fully compatible with HPOS.
Our plugin correctly exports custom fields (added by 3rd-party plugins) in most cases. But for some complex plugins -  you should [use snippet](https://docs.algolplus.com/algol_order_export/developers-algol_order_export/codes-for-plugins-developers-algol_order_export/overview-17/).


== Installation ==

= Automatic Installation =
Go to WordPress dashboard, click  Plugins / Add New  , type 'Advanced Order Export For WooCommerce' and hit Enter.
Install and activate plugin, visit WooCommerce > Export Orders.

= Manual Installation =
[Please, visit the link and follow the instructions](https://wordpress.org/documentation/article/manage-plugins/#manual-plugin-installation-1)

== Frequently Asked Questions ==
The plugin is fully compatible with HPOS.

Please, review [user guide](https://docs.algolplus.com/category/algol_order_export/) at first.

Check [some snippets](https://docs.algolplus.com/category/algol_order_export/developers-algol_order_export/codes-for-plugins-developers-algol_order_export/) for popular plugins or review  [this page](https://docs.algolplus.com/category/algol_order_export/developers-algol_order_export/code-samples-developers-algol_order_export/) to study how to extend the plugin.

Still need help? Create ticket in [helpdesk system](https://algolplus.freshdesk.com). Don't forget to attach your settings or some screenshots. It will significantly reduce reply time :)

= I want to add a product attribute to the export  =
Check screenshot #5! You should open section "Set up fields", open section "Product order items"(right column), click button "Add field", select field in 1st dropdown, type column title and press button "Confirm".

= Same order was exported many times =
You should open section "Set up fields to export" and set "Fill order columns for" to  "1st row only". The plugin repeats common information for each order item (by default).

= I see only GREEN fields in section "Set up fields"  =
Please, unmark checkbox "Summary Report By Products" (it's below date range)

= Red text flashes at bottom during page loading = 
It's a normal situation. The plugin hides this warning on successful load. 

= I can't filter/export custom attribute for Simple Product =
I'm sorry, but it's impossible. You should add this attribute to Products>Attributes at first and use "Filter by Product Taxonomies".

= How can I add a Gravity Forms field to export? =
Open order, look at items and remember meta name.
Visit WooCommerce>Export Orders,
open section "Set up fields", open section "Product order items"(at right), click button "Add field",
select SAME name in second dropdown (screenshot #5)

= Plugin produces unreadable XLS file =
The theme or another plugin outputs some lines. Usually, there are extra empty lines at the end of functions.php(in active theme).

= I can't export Excel file (blank message or error 500) =
Please, increase "memory_limit" upto 256M or ask hosting support to do it.

= When exporting .csv containing european special characters , I want to open this csv in Excel without extra actions =
You  should open tab "CSV" and set up ISO-8859-1 as codepage.

= Preview shows wrong values,  I use Summary mode =
This button processes only first 5 orders by default, so you should run the export to see correct values.

= Where does free version save files? = 
Free version doesn't save generated file on your webserver, you can only download it using browser.

= Can I request any new feature ? =
Yes, you can email a request to aprokaev@gmail.com. We intensively develop this plugin.

== Screenshots ==

1. Default view after installation.  Just click 'Express Export' to get results.
2. Filter orders by many parameters, not only by order date or status.
3. Select the fields to export, rename labels, reorder columns.
4. Button Preview works for all formats.
5. Add custom field or taxonomy as new column to export.
6. Select orders to export and use "bulk action".

== Changelog ==

= 4.0.7 - 2026-04-07 =
* New field "Item cost before discount(inc tax)" (>Setup Fields>Product Order Items)
* Autoscale supported for image height, PDF format
* Fixed bug - impossible to sort orders by shipping/billing fields, in HPOS mode only
* Updated external libraries (to fix PHP 8.5 errors) 

= 4.0.6 - 2025-12-03 =
* Added a "Setup Fields" button at the top of the form
* Correctly display error messages if the XML module (for PHP) is not installed
* Fixed bug - "Exclude Free Items" option was exporting orders without products
* Fixed bug - impossible to disable options for JSON format
* Fixed bug - deprecation warnings displayed by WooCommerce 10.3
* Fixed bug - some messages displayed incorrect HTML due to unnecessary output escaping

= 4.0.5 - 2025-09-03 =
* Requires PHP 7.4+
* Updated library used to generate Excel files

= 4.0.4 - 2025-09-01 =
* Fixed bug - option "Skip fully refunded items" conflicted with mode "Export refunds"
* Fixed bug - fatal error when exporting refunds (if main order is missing)

= 4.0.3 - 2025-07-07 =
* Mode "Export refunds" supports product filters
* New option "Skip order having any excluded products" (>Filter by product)
* New fields "Brand", "Cost of goods" (>Setup Fields>Products)
* Fixed bug - field "Coupon description" was empty
* Fixed bug - duplicates in export if many orders have same dates
* Fixed bug - PHP functions (added to section "Misc Settings") shown error "Cannot redeclare function"
* Fixed bug - incorrectly declared HPOS compatibility

= 4.0.2 - 2025-04-01 =
* Fixed critical bug - fatal error on page load (if WooCommerce runs in mode "WordPress posts storage (legacy)")
* Fixed critical bug - conflict with other plugins used outdated versions of same libraries ("FileBird Pro" and etc)

= 4.0.1 - 2025-03-26 =
* Uses new library to generate Excel files. If you created custom code to format Excel files - please migrate from [PHPExcel to PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/en/latest/topics/migration-from-PHPExcel/#manual-changes)
* Fixed broken bulk actions

= 3.6.0 - 2025-03-04 =
* Changed behavior for mode "Export refunds"!
* New field "New or Returning" (>Setup Fields>Customer)
* Added option "Auto row height" to section "XLS"
* Fixed bug - product filters  were applied to export via "Bulk actions"
* Fixed bug - wrong sorting for XLS/PDF formats, in mode "Export without progress"

= 3.5.8 - 2025-01-28 =
* Internal, not released

= 3.5.7 - 2025-01-20 =
* Added checkbox "Display summary row" to section "Setup Fields"
* Added option "Exclude free items" to section "Filter by item and metadata"
* New field "Quantity (Refunded)" (>Setup Fields>Product Order Items)
* New field "Tax Rates" (>Setup Fields>Product Order Items)
* Added tip (with expected action) for new field created via >Setup Fields>Add Field
* Fixed bug - wrong values in field "Currency symbol"
* Fixed bug - critical error for XLS format if sorting by numeric field
* Fixed bug - option "Export all products from the order" now ignored if all product filters are empty
* Fixed PHP8.4 notices and warnings

= 3.5.6 - 2024-11-11 =
* Fixed "PHP Object Injection" (CVE-2024-10828). Thank [@webbernaut](https://profiles.wordpress.org/webbernaut/) for reporting this vulnerability!
* New field "Cart Discount Amount(inc. tax)" (>Setup Fields>Cart)
* Fixed bug - field "Embedded product image" was empty if some CDNs were active

= 3.5.5 - 2024-10-11 =
* New field "GTIN/EAN" (>Setup Fields>Products)
* Hide item meta started with underscore, by default
* Fixed bug - extra html in item meta
* Minor bugs

= 3.5.4 - 2024-09-18 =
* Added extra checks and made error messages more informative
* Use user_id as grouping key for mode "Summary Report by Customers", billing email is still used for orders made by guests
* Fixed bug - option  "Skip Suborders" suppressed option "Export Refunds"
* Fixed bug - field "Product Variation" was wrong (some woocommerce hooks were not applied to it)
* Fixed bug - empty field "Total amount(inc tax)"  for mode "Summary Report By Products"
* Fixed bug - some temporary files were not deleted after exporting XLS/PDF files
* Fixed bug - XLS failed to export arrays, in modes "Summary Report By Products/Customers"

= 3.5.3 - 2024-06-03 =
* Fixed PHP8 notices and warnings
* Fixed non-reported bugs, detected by PHPStan

= 3.5.2 - 2024-05-27 =
* XLS/PDF formats support AVIF product images
* Added "Stop renewal after" and "Subscription price" fields to >Setup Fields>Products (if Woo Subscriptions is active)
* Fixed bug - empty "Custom Fields" dropdown in section "Filter by order" (HPOS mode, big shops only)
* Fixed bug - missed header line for XLS/PDF if nothing to export
* Removed inactive suspicious function to avoid false warnings from security plugins

= 3.5.1 - 2024-04-25 =
* Reduced page loading time for stores with a huge number of orders
* Fixed bug - can't mark/unmark exported orders if sync with legacy is off (HPOS mode)
* Fixed bug - can't filter orders by "_billling" / "_shipping" order meta (HPOS)
* Fixed bug - can't filter orders by "_payment_method" order meta (HPOS)
* Fixed bug - sections "Filter by billing/shipping" displayed empty dropdowns (HPOS)
* Fixed bug - field "customer_user" is 0 for guests now (reverted change)
* Fixed bug - some metas  can not be read for orders (legacy mode)
* Fixed bug - customer stats was different in HPOS and legacy mode
* Fixed bug - PHP warnings for "Coupon description" field
* All dropdowns are searchable in section "Setup Fields"

= 3.5.0 - 2024-04-03 =
* The plugin requires at least WooCommerce 4.0.0
* Fixed bug - some address fields were empty for refunds
* Fixed bug - option "Shipping fields use billing details" ignored fields "Shipping Company" and "Shipping Phone"
* Fixed minor bugs, only for WooCommerce in legacy mode

= 3.4.6 - 2024-03-25 =
* New field "Origin" (>Setup Fields>Common)
* XLS format supports .webp product images
* Fixed bug - DESC sorting didn't work for number/money fields (XLS/PDF formats)
* Fixed bug - PHP 8.1 errors for XLS format
* Fixed bug - empty section "Custom Fields" in "Filter by order", if shop has 1000+ orders

= 3.4.5 - 2024-01-10 =
* Fixed RCE vulnerability
* Tweaked PDF format
* Fixed bug - sorting by Order fields didn't work for XLS/PDF
* Fixed bug - PHP warnings for address fields

= 3.4.4 - 2023-11-27 =
* Fixed critical bug - some columns were empty (XLS format only)
* Added field "Full Address" to sections Billing and Shipping
* Minor UI tweaks in mobile view
* Fixed bug - >Filter by order>Custom Fields didn't work, HPOS mode

= 3.4.3 - 2023-11-14 =
* Speed up calculation for fields "Customer Total Orders", "Customer Total Amount" in "Summary report by customers" mode
* Added operator NOT LIKE, for filtering by user fields and order fields
* Added compatibility with plugin "Transients Manager"
* Replaced confusing icon "Σ" with text "Sum"
* Fixed bug - incorrect timezones used in filtering by date, HPOS mode
* Fixed bug - option "Shipping fields use billing details" didn't work, HPOS mode
* Fixed bug - empty address fields for order refunds, HPOS mode
* Fixed bug - date fields were wrongly formatted if timestamp used in database
* DEV - moved common code from Extractor and Extractor_UI classes to traits

= 3.4.2 - 2023-07-26 =
* PDF format supports .webp product images
* Fixed bug - missed Bulk Actions in >WooCommerce>Orders (HPOS mode)
* Fixed bug - option "Do not set a page break between order lines" worked wrongly for PDF
* Fixed bug - field "Customer Role" was empty if user has multiple roles
* Fixed bug - PHP8 warnings and errors for XLS format
* Fixed bug - PHP8 warnings for PDF export

= 3.4.1 - 2023-04-11 =
* Internal, not released

= 3.4.0 - 2023-03-13 =
* Support High-Performance order storage (COT)
* Added field "Customer Paid Orders"
* Fixed bug - filter by paid/completed date ignored DST
* Fixed bug - role names were not translated in field "User role"
* Fixed bug - field format was ignored for fields added via  >Setup Fields>Customer>Add Field
* Fixed bug - capability "edit_themes " was not checked when importing JSON configuration via tab Tools
* Fixed PHP8 deprecation warnings for JSON,XML formats
