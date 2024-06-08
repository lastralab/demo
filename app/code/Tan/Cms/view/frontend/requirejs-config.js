var config = {
    map: {
        '*': {
            fadeInElement: 'Tan_Cms/js/fade-in-element',
            slideShow: 'Tan_Cms/js/slide-show'
        }
    },
    paths: {
        'vue': [
            'Tan_Cms/js/vue'
        ]
    },
    shim: {
        'Tan_Cms/js/jquery-log': ["jquery"]
    },
    deps: ["Tan_Cms/js/every-page"],
    config: {
        "mixins": {
            "Magento_Ui/js/view/messages": {
                "Tan_Cms/js/messages-mixin": true
            }
        }
    }
}
