=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: meccaproduction.com
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

Custom plugin for online ordering process for Woo Commerce.

== Features ==

1. Smart delivery - Calculates delivery time based on shipping address and location of restuarant using Google Maps Geocode API and Google Maps Distance Matrix API, then takes into account other variables, such as preptime per pizza and cooktime per pizza.
2. Product specials - Field added to product for if it is a special. Slider window is available to display products. 
3. Custom Order Names - Added option for order name so customer can add name to order for easy re-order in the future. 
4. Custom templates for products and ordering pages
5. Minimum Delivery Amount - Added option that is set in the settings page.  Also checks in AJAX shipping method call to see if the customer selected Take Out
6. Reorder - Button on online order detail page, which grabs the order ID, queries items from the order, and redirects customer to the cart page with the items in their cart.
7. Future Orders - Customers can select a future order date on their order which will display on the admin order page
8. Omnivore - Integrates with omnivore API to import products from client's POS

== Future Updates ==

- [ ] Parent / Child product relationship for pizza toppings
- [ ] Fields on product for type of product 
- [ ] Build your pizza 
- [ ] Template for product categories
- [ ] Template for base products page and shortcodes 
- [ ] Add minimum delivery subtotal