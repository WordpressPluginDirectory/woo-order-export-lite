=== Advanced Order Export For WooCommerce ===
Contributors: algolplus
Donate link: 
Tags: order export,export orders,woocommerce,order,export
Requires PHP: 8.1.0
Requires at least: 4.7
Tested up to: 6.8
Stable tag: 4.0.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Export WooCommerce orders to Excel/CSV/XML/JSON/PDF/HTML/TSV

== Description ==
This plugin helps you to **easily** export WooCommerce order data. 

Export any custom field assigned to orders/products/coupons is easy and you can select from various formats to export the data in such as CSV, XLS, XML and JSON.

= Features =

* **select** the fields to export
* **rename** labels
* **reorder** columns 
* export WooCommerce **custom fields** or terms for products/orders
* mark your WooCommerce orders and run "Export as..." a **bulk operation**.
* apply **powerful filters** and much more

= Export Includes =

* order data
* summary order details (# of items, discounts, taxes etc…)
* customer details (both shipping and billing)
* product attributes
* coupon details
* XLS, CSV, TSV, PDF, HTML, XML and JSON formats

= Use this plugin to export orders for =

* sending order data to 3rd part drop shippers
* updating your accounting system
* analysing your order data


Have an idea or feature request?
Please create a topic in the "Support" section with any ideas or suggestions for new features.

> Pro Version

> Are you looking to have your WooCommerce products drop shipped from a third party? Our plugin can help you export your orders to CSV/XML/etc and send them to your drop shipper. You can even automate this process with [Pro version](https://algolplus.com/plugins/downloads/advanced-order-export-for-woocommerce-pro/?currency=USD) .



== Installation ==

= Automatic Installation =
Go to WordPress dashboard, click  Plugins / Add New  , type 'order export lite' and hit Enter.
Install and activate plugin, visit WooCommerce > Export Orders.

= Manual Installation =
[Please, visit the link and follow the instructions](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation)

== Frequently Asked Questions ==

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
* Requires PHP 8.1+
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

= 3.3.3 - 2022-10-24 =
* Fixed CSRF vulnerability
* Added option "Strip tags from all fields" to section "Misc settings"
* The "Link to edit order" field works for XLS format
* Fixed bug - "Remove line breaks" option incorrectly replaced commas with spaces
* Fixed bug - "Sum Items Exported" field was empty for XLS/PDF formats, mode "Summary report by products"
* Fixed bug - PHP warning if all fields have undefined format
* Updated Select2.js library

= 3.3.2 - 2022-08-08 =
* Fixed XSS vulnerability
* Fixed bug - filter "Orders Range" ignores space chars now
* Fixed bug - export failed  if product used webp images

= 3.3.1 - 2022-05-23 =
* Fixed critical bug - mode "Add coupons as X columns" exported empty product data

= 3.3.0 - 2022-05-18 =
* Allow to sort by any field, for XLS/PDF formats only
* Output summary row, for XLS/PDF formats only
* Added fields "Phone (Shipping)", "Currency Symbol", "Subscription Relationship"
* Added fields "Qty-Refund","Amount-Refund", "Total Amount (inc. tax)" for "Summary report by products"
* Fixed bug - added workaround for last versions of PHP 8.0 and 8.1, they have bug for ob_clean() 
* Fixed bug - option "Remove emojis" damaged last product in export
* Fixed bug - field type "Link" ignored for XLS format
* Fixed bug - long text (for some languages) breaks layout for section Setup Fields
* Fixed bug - can't correctly export custom attribute if it was unused in variations
* Fixed bug - wrong fee amount exported , in rare cases
* Fixed bug - incorrect export for mode "Add products as XX columns", rare case too
* Fixed bug - page was not loaded if website has 10,000+ coupons

= 3.2.2 - 2021-12-14 =
* Fixed bug - PHP8 compatibility issues (deprecation warnings for XLS format)
* Fixed bug - blank row was added after every 1000 rows (XLS format)
* Fixed bug - money cells were empty if value = 0 (XLS format)
* Fixed bug - products were not sorted by Name in summary mode
* Fixed bug - some files were not deleted in folder /tmp

= 3.2.1 - 2021-11-11 =
* Fixed critical bug - option "Format numbers" broke XLS format

= 3.2.0 - 2021-11-09 =
* Speeded up XLS export
* Added option "Remove emojis" (XLS format)
* Added option "Remove line breaks" (CSV format)
* Added field "Total volume"
* New button "Add calculated field" in section "Setup Fields"
* Fixed bug - photo missed in product search
* Fixed bug - can't filter items if item has "&" in name
* Fixed bug - PHP warnings for deleted taxonomy
* Fixed bug - long links broke PDF cells
* Fixed bug - ignored capability "export_woocommerce_orders"

= 3.1.9 - 2021-06-22 =
* New field "Sum of Items (Exported)" for mode "Summary Report By Customers"
* Added extra operators for filter by item meta
* Correctly export description of variation
* Correctly show alias for deleted role
* Fixed bug - fatal error if variation was deleted
* Fixed bug - unixtimestamp exported as number (not date) to Excel
* Fixed bug - option "Export only matched product items" didn't work if order has variations of same product
* Fixed bug - TAX fields (added via >Setup Fields>Other items) ignored shipping amount 

= 3.1.8 - 2021-02-22 =
* Fixed XSS vulnerability
* Screen >WooCommerce>Orders can be sorted by column "Export Status"
* New field "Order subtotal + Cart tax amount"
* New field "Shipping Zone" 
* Added operators "IS SET" and "NOT SET" for item meta filters
* Added option "Don't encode unicode chars" to section "JSON "
* Fixed bug - some compatibility issues with PHP 7.4
* Fixed bug - correctly support Loco Translate
* Fixed bug - weight was rounded for XLS format

= 3.1.7 - 2020-12-09 =
* New field "Summary Report Total Weight"
* Added option to round "Item Tax Rate" (Misc Settings)
* Added option "Force enclosure for all values" (CSV format)
* Use billing email to calculate field "Customer Total Orders" for guests
* The plugin supports capability "export_woocommerce_orders"
* Fixed bug - PDF text didn't fit to cell by column width
* Fixed bug - field "Non variation attributes" showed wrong values for existing taxonomies

= 3.1.6 - 2020-09-21 =
* New product fields "Item Cost (inc. tax)", "Stock Status", "Stock Quantity", "Non variation attributes"
* New customer field "Customer Total Spent"
* Added option "Add links to images" (HTML format)
* Fixed bug - duplicates were shown in "Summary by products" mode
* Fixed bug - field "Coupon Discount Amount" was empty
* Fixed bug - fatal PHP error "Call to undefined method get_duplicate_settings()"
 
= 3.1.5 - 2020-08-24 =
* Compatible with PHP 7.4
* Added option "Format output" (XML format)
* Added option "Don't break order lines by 2 pages" (PDF format)
* Added option "Add links to images" (PDF format)
* Added option "Try to convert serialized values" (Misc Settings)
* Added fields "Summary Report Total Fee Amount", "Summary Report Total Tax Amount"
* Fixed bug - 'wc doing it wrong' notice (direct access to product parent property)
* Fixed bug - option "Change order status" worked only for button "Export w/o progress"
* Fixed bug - option "Add products as " = "0 columns" incorrectly worked for button "Export"
* Fixed bug - field "Embedded Product Image" showed parent image for variation
* Fixed bug - mode "Summary Report By Products" incorrectly worked with variations
* Fixed bug - custom and static fields were empty in "Summary by customers" mode
* Fixed bug - draft products were visible in autocomplete
* Fixed bug - button "Import" was shown as disabled at tab "Tools"
* New hooks for PDF format

= 3.1.4 - 2020-04-15 =
* Prevent XSS attack (CVE-2020-11727). Thank Jack Misiura​ for reporting this vulnerability!

= 3.1.3 - 2020-03-24 =
* Fixed CRITICAL bug - export via "Bulk actions" (at screen >WooCommerce>Orders) works incorrectly

= 3.1.2 - 2020-03-16 =
* Added filter by order IDs (not order numbers!)
* Added checkbox "Export only matched product items" to section "Filter by item and metadata"
* Added checkbox "Shipping fields use billing details (if shipping address is empty)" to section "Misc Settings"
* Added fields "Item Cost Before Discount", "Item Discount Tax" to section "Product order items"
* Renamed field "Product Variation" to "Order Item Metadata"
* Added some tooltips to sections inside "Set up fields"
* Support tag {order_number} in filename
* Fixed UI bugs for Firefox
* Fixed bug - Preview was wrong if CSV format used non-UTF8 codepage
* Fixed bug - some warnings in JS console
* Fixed bug - Safari added .csv to any filename when we use TSV format
* Fixed bug - wrong filters applied when user selected orders and exported them via bulk action
* New hooks for product custom fields

= 3.1.1 - 2019-11-18 =
* Field "Embedded product image" is exported by "Summary by product" mode (XLS/PDF/HTML formats)
* Added checkbox to export item rows with a new line (TAB format)
* Fixed incompatibility with "Advanced Custom Fields" plugin
* Fixed bug - product static fields were empty sometimes
* Fixed bug - adding fields worked incorrectly at tab "Product items"
* Fixed bug - fields "Categories" and "Full names for categories" were empty for variable products

= 3.1.0 - 2019-11-11 =
* Speeded up page loading and button "Preview"
* Added filter "Products SKU" to section "Filter by product"
* Added options for JSON format
* Added vertical align for cells (PDF format)
* New tabs "Product items", "Product totals" in section "Setup fields"
* Order fields can be dragged to section "Products" (JSON/XML formats)
* Added product field "SKU(parent)"
* Added fields "Total Shipping","Total Discount","Total Items" for "Summary by customers" mode
* Support "0" as max # of product columns (calculated based on exported orders)
* Deleted products are exported by "Summary by products" mode 
* Fixed UI bugs for summary mode
* New hooks for PDF format
* Fixed bug - sorting (by order fields) conflicted with filtering by order custom fields

= 3.0.3 - 2019-08-29 =
* Fixed CRITICAL bug - export wrong data if user added customer field "First Order Date" or "Last Order Date"
* Fixed bug - customer fields "First Order Date" or "Last Order Date" were empty for guests
* Fixed bug - wrong height for cells (PDF format only)

= 3.0.2 - 2019-08-20 =
* Added "Summary by customers" report 
* Format PDF supports UTF8 chars
* Added filter "Exclude products" to section "Filter by product"
* New tab "Other items" (in section "Setup fields")  allows to export tax/fee/shipping
* Fixed bug - XLS export stops at wrong dates
* Fixed bug - button "ESC" doesn't abort export (Safari only)

= 3.0.1 - 2019-07-22 =
* Added product field "Product Name (main)" to export name of variable product (not name of variation!)
* Added summary product fields to export discounts and refunds
* Fixed bug - bulk exporting from orders page didn't work if you set date range filter at page "Export Now"
* Fixed bug - it was impossible to add custom field at tab "User"
* Fixed bug - filter "User roles" applied incorrectly
* Fixed bug - filter "Item meta" showed wrong results if you tried to filter by different meta keys

= 3.0.0 - 2019-07-03 =
* New format - **HTML**
* Added order field "Link to edit order" (useful for HTML format)
* Added product field "Embedded Product Image" (works for XLS and PDF formats only!)
* Added order fields (for customer) -  "First Order Date", "Last Order Date"
* Added 'Hide unused' for order/product/coupon fields (dropdowns filtered by matching orders)
* Allow to sort orders by any custom field
* Fixed bug - fields with prefix "USER_" were shown for all tabs in section "Setup fields" 
* Fixed bug - the plugin exported all orders by default (including cancelled and refunded) 
* Fixed bug - bulk export didn't sort orders
* Fixed bug - incompatibility with some coupon plugins
* Fixed bug - tab "Tools" didn't show error if JSON is not valid
* Removed a lot of outdated code
