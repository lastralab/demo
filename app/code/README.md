# Custom Modules

__Tan_InitConfig__
* Sets basic configuration values in core_config_data

__Tan_InitTheme__
* Wishlist link template

__Tan_InitCatalog (pending)__
* Creates one of each product types: simple, bundle, virtual
    * Creates demo categories and subcategories
    * Creates demo attributes
    * Creates demo products
        * Adds images to some products
        * Adds stock
        * Assigns products to categories

__User Journeys__
* Frontend:

https://github.com/lastralab/demo/assets/22894897/499b1ffe-1e9b-4ab1-9d5c-2322d4411fd9

* Backend:

https://github.com/lastralab/demo/assets/22894897/41fdc136-baa5-4f3f-805b-8f2d8c4f310a


___Modules Summary___

__Tan_Catalog__
* Creates Category Filter Service
    * getCategoryIdByName()

__Tan_Backend__
* Creates a demo configuration to set messages in the frontend based on local temperature

__Tan_Checkout__
* Adds a button to the minicart to empty cart

__Tan_Cms__
* Experimenting with Vue.js
    * Log message on all pages
    * Replace weather message placeholder with temperature data and config values from the backend
    * Adds lazy loading effect to all images
    * Creates a widget to display 3 images in a slideshow [TAN-0085](https://github.com/lastralab/demo/issues/85)
* Apply coupon code when weather is "bad" [TAN-0084](https://github.com/lastralab/demo/issues/84)


__Tan_Customer__
* Adds Orders link to the header
* Replaces some links with fontawesome icons

__Tan_LayeredNavigation__
* Sets sidebar to move while scrolling
* Changes title/subtitle

__Tan_Review__
* Limits reviews to only customers who have bought that product before

__Tan_Sales__
* Removes action column for recent orders
* Order can be viewed by clicking on its order ID number

__Tan_TagLine__
* Adds subtitle to product name in product page [TAN-0089](https://github.com/lastralab/demo/issues/89)

__Tan_Theme__
* Adds compare items link to the top links section
* Adds 'go to top' link at the bottom for mobile
* Adds block next to the logo to display the store name and link it to the contact us page
* Adds wishlist link to the header, before the orders link

__Tan_Wishlist__
* Displays the products as a table
