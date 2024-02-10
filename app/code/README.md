# Custom Modules

__Tan_InitConfig__
* Sets basic configuration values in core_config_data

__Tan_Theme__
* Wishlist link template
* ![image](https://github.com/lastralab/demo/assets/22894897/ead96270-7415-43e3-8b50-81d6dd9ccf4d)

__Tan_InitCatalog__
* Creates one of each product types (except grouped)
    * Creates demo categories and subcategories
    * ![image](https://github.com/lastralab/demo/assets/22894897/74272a7e-080e-4ef5-a9e1-f848f7eb04ba)
    * Creates demo attributes
    * ![image](https://github.com/lastralab/demo/assets/22894897/71b48287-e003-488a-9012-277e9dcfe940)
    * Creates demo products (pending)
        * Adds images to a demo configurable product
        * Adds stock
        * Assigns products to categories

__Tan_InitTheme__
* Assigns loaded theme matching code to default store

__Tan_Catalog__
* Creates Category Filter Service
    * getCategoryIdByName()
    * Using xdebug + Lastra_Testing:
        * ![image](https://github.com/lastralab/demo/assets/22894897/016f9f6d-a699-4701-975c-84cfc9a1e229)

__Tan_Customer__
* Replaces top links with icons
* Shows header links based on customer session

__Tan_Checkout__
* Adds 'empty cart' link to remove all items in minicart
