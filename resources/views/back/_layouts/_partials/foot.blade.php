<!-- project specific includes -->
@include('chief::back._layouts._partials.project-footer')

<script src="{{ chief_cached_asset('/chief-assets/back/js/main.js') }}"></script>

<!-- place to add custom vue components, right before the global Vue instance is created -->
@stack('custom-components')

<!-- radio options component, todo: place in component file instead of inline -->
<script>
    Vue.component('radio-options',{
        props: ['errors', 'defaultType'],
        data: function(){
            return {
                type: this.defaultType,
            };
        },
        methods:{
            changeType: function(type){
                this.type = type;
            },
        }
    });
</script>


@stack('custom-scripts')

<script>

    /**
     * Global Eventbus which allows to emit and listen to
     * events coming from components
     */
    window.Eventbus = new Vue();

    /**
     * Application vue instance. We register the vue instance after our custom
     * scripts so vue components are loaded properly before the main Vue.
     */
    window.App = new Vue({
        el: "#main",
        data: {
            errors: new Errors(),
        },
        created: function(){
            this.errors.record({!! isset($errors) ? json_encode($errors->getMessages()) : json_encode([]) !!});

            Eventbus.$on('clearErrors', (field) => {
                this.errors.clear(field);
            });
        },
        methods:{
            showModal: function(id, options){
                return window.showModal(id, options);
            },
            closeModal: function(id){
                return window.closeModal(id);
            },
            closeDropdown: function(id){
                return window.closeDropdown(id);
            },
            selectTab: function(hash){
                Eventbus.$emit('select-tab',hash);
            },
            clear: function(field){
                Eventbus.$emit('clearErrors', field)
            }
        },
    });

    window.showModal = function(id, options){
        Eventbus.$emit('open-modal', id, options);
    };

    window.closeModal = function(id){
        Eventbus.$emit('close-modal',id);
    };

    // Close dropdown outside of the dropdown - used by backdrop
    window.closeDropdown = function(id){
        Eventbus.$emit('close-dropdown',id);
    };

</script>

<script src="{{ chief_cached_asset('/chief-assets/back/js/native.js') }}"></script>
@stack('custom-scripts-after-vue')

</body>
</html>