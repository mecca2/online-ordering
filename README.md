# Online Ordering Plugin for Pizza Restuarants
This plugin adds features to woocommerce shopping cart. 

## Requirements 
- Wordpress 
- Woocommerce

## Features 
- Smart delivery 
-- Calculates delivery time based on shipping address and location of restuarant using Google Maps Geocode API and Google Maps Distance Matrix API, then takes into account other variables, such as preptime per pizza and cooktime per pizza.
- Product specials 
-- Field added to product for if it is a special. Slider window is available to display products. 
- Re-Order 
- Order Name 
-- Added option for order name so customer can add name to order for easy re-order in the future. 
- Custom templates for products and ordering pages
-- Added minimum delivery amount option that is set in the settings page.  Also checks in AJAX shipping method call to see if the customer selected Take Out
-- Added Re-order functionality on the order-online page, which grabs the order ID, queries items from the order, and redirects customer to the cart page with the items in their cart.

## To-Do 
- [ ] Parent / Child product relationship for pizza toppings
- [ ] Fields on product for type of product 
- [ ] Build your pizza 
- [ ] Template for product categories
- [ ] Template for base products page and shortcodes 
- [ ] Add minimum delivery subtotal
