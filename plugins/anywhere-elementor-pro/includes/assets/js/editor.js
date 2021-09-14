jQuery(document).ready(function (){
    elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
        var widget_type = model.attributes.widgetType;

        if(widget_type == 'ae-acf-repeater') {
            jQuery(document).on('change', "select[data-setting='layout_mode']", function () {
                //jQuery('settings[data-setting="ae_post_type"]').select2().trigger('change');
                elementor.reloadPreview();
            });
        }

        if(widget_type == 'ae-acf'){
            jQuery('.elementor-control-_skin label').html('Field Type');
        }

        if(widget_type == 'ae-pods'){
            jQuery('.elementor-control-_skin label').html('Field Type');
        }

        if(widget_type == 'ae-post-blocks-adv'){
            jQuery('.elementor-control-_skin label').html('Layout');

        }

        if(widget_type == 'ae-post-blocks'){
            jQuery(document).on('change', "select[data-setting='layout_mode']",  function () {
                //jQuery('settings[data-setting="ae_post_type"]').select2().trigger('change');
                //elementor.reloadPreview();
            });

            selected_post_ids = model.attributes.settings.attributes.ae_post_ids;

            // get selected data
            jQuery.ajax({
                url: aepro.ajaxurl,
                dataType: 'json',
                method: 'post',
                data: {
                    selected_posts : selected_post_ids,
                    action: 'ae_post_data',
                    fetch_mode: 'selected_posts'
                },
                success: function(res){
                    options = '';
                    if(res.data.length) {
                        jQuery.each(res.data, function (key, value) {
                            options += '<option selected value="' + value['id'] + '">' + value['text'] + '</option>';
                        });
                    }



                    jQuery("select[data-setting='ae_post_ids']").html(options).select2({
                        ajax: {
                            url: aepro.ajaxurl,
                            dataType: 'json',
                            data: function (params) {
                                return {
                                    q: params.term,
                                    action: 'ae_post_data',
                                    fetch_mode: 'posts'
                                }
                            },
                            processResults: function (res) {
                                return {
                                    results: res.data
                                }
                            }
                        }    ,
                        minimumInputLength: 2
                    });
                }
            });
        }
    } );
});



(function (elementor, $, window) {
	// Query Control

	var Query = elementor.modules.controls.Select2.extend({
		cache: null,
		isTitlesReceived: false,

		getSelect2Placeholder: function getSelect2Placeholder() {
			var self = this;

			return {
				id: '',
				text: self.model.get("placeholder") || "All",
			};
		},

		getSelect2DefaultOptions: function getSelect2DefaultOptions() {
			var self = this;

			return jQuery.extend(
				elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply(
					this,
					arguments
				),
				{
					ajax: {
						transport: function transport(params, success, failure) {
							var data = {
								q: params.data.q,
								query_type: self.model.get("query_type"),
								query_options: self.model.get("query_options"),
								object_type: self.model.get("object_type"),
							};

							return elementorCommon.ajax.addRequest(
								"aep_query_autocomplete_data",
								{
									data: data,
									success: success,
									error: failure,
								}
							);
						},
						data: function data(params) {
							return {
								q: params.term,
								page: params.page,
							};
						},
						cache: true,
					},
					escapeMarkup: function escapeMarkup(markup) {
						return markup;
					},
					minimumInputLength: 1,
				}
			);
		},

		getValueTitles: function getValueTitles() {
			var self = this,
				ids = this.getControlValue(),
				queryType = this.model.get("query_type"),
				queryOptions = this.model.get("query_options"),
				objectType = this.model.get("object_type");

			if (!ids || !queryType) return;

			if (!_.isArray(ids)) {
				ids = [ids];
			}

			elementorCommon.ajax.loadObjects({
				action: "aep_query_value_titles",
				ids: ids,
				data: {
					query_type: queryType,
					query_options: queryOptions,
					object_type: objectType,
					unique_id: "" + self.cid + queryType,
				},
				success: function success(data) {
                    
					self.isTitlesReceived = true;
					self.model.set("options", data);
					self.render();
				},
				before: function before() {
					self.addSpinner();
				},
			});
		},

		addSpinner: function addSpinner() {
			this.ui.select.prop("disabled", true);
			this.$el
				.find(".elementor-control-title")
				.after(
					'<span class="elementor-control-spinner pp-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>'
				);
		},

		onReady: function onReady() {
			setTimeout(
				elementor.modules.controls.Select2.prototype.onReady.bind(this)
			);

			if (!this.isTitlesReceived) {
				this.getValueTitles();
			}
		},

		onBeforeDestroy: function onBeforeDestroy() {
			if (this.ui.select.data("select2")) {
				this.ui.select.select2("destroy");
			}

			this.$el.remove();
		},
	});

	// Add Control Handlers
	elementor.addControlView( 'aep-query', Query );

	
} )( elementor, jQuery, window );
