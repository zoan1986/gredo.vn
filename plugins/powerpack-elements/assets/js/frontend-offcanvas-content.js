;(function($) {

	PPOffcanvasContent = function( $scope ) {
		
		this.node                 = $scope;
		this.wrap                 = $scope.find('.pp-offcanvas-content-wrap');
		this.content              = $scope.find('.pp-offcanvas-content');
		this.button               = $scope.find('.pp-offcanvas-toggle');
		this.settings             = this.wrap.data('settings');
		this.toggle_source		  = this.settings.toggle_source;
		this.id                   = this.settings.content_id;
		this.toggle_id			  = this.settings.toggle_id;
		this.toggle_class		  = this.settings.toggle_class;
		this.transition           = this.settings.transition;
		this.esc_close            = this.settings.esc_close;
		this.body_click_close     = this.settings.body_click_close;
		this.direction            = this.settings.direction;
		this.duration             = 500;

		this.destroy();
		this.init();
	};

	PPOffcanvasContent.prototype = {
		id: '',
		node: '',
		wrap: '',
		content: '',
		button: '',
		settings: {},
		transition: '',
		duration: 400,
		initialized: false,
		animations: [
			'slide',
			'slide-along',
			'reveal',
			'push',
		],

		init: function () {
			if ( ! this.wrap.length ) {
				return;
			}

			$('html').addClass('pp-offcanvas-content-widget');

			if ( $('.pp-offcanvas-container').length === 0 ) {
				$('body').wrapInner( '<div class="pp-offcanvas-container" />' );
				this.content.insertBefore('.pp-offcanvas-container');
			}

			if ( this.wrap.find('.pp-offcanvas-content').length > 0 ) {
                if ( $('.pp-offcanvas-container > .pp-offcanvas-content-' + this.id).length > 0 ) {
                    $('.pp-offcanvas-container > .pp-offcanvas-content-' + this.id).remove();
                }
                if ( $('body > .pp-offcanvas-content-' + this.id ).length > 0 ) {
                    $('body > .pp-offcanvas-content-' + this.id ).remove();
                }
                $('body').prepend( this.wrap.find('.pp-offcanvas-content') );
			}

			this.bindEvents();
		},

		destroy: function() {
			this.close();

			this.animations.forEach(function( animation ) {
				if ( $('html').hasClass( 'pp-offcanvas-content-' + animation ) ) {
					$('html').removeClass( 'pp-offcanvas-content-' + animation )
				}
			});

			if ( $('body > .pp-offcanvas-content-' + this.id ).length > 0 ) {
				//$('body > .pp-offcanvas-content-' + this.id ).remove();
			}
		},

		setTrigger: function() {
			var $trigger = false;

			if ( this.toggle_source == 'element-id' && this.toggle_id != '' ) {
				$trigger = $( '#' + this.toggle_id );
			} else if ( this.toggle_source == 'element-class' && this.toggle_class != '' ) {
				$trigger = $( '.' + this.toggle_class );
			} else {
				$trigger = this.node.find( '.pp-offcanvas-toggle' );
			}
			
			return $trigger;
		},

		bindEvents: function () {
			$trigger = this.setTrigger();

			if ( $trigger ) {
				$trigger.on('click', $.proxy( this.toggleContent, this ));
			}

			$('body').delegate( '.pp-offcanvas-content .pp-offcanvas-close', 'click', $.proxy( this.close, this ) );
			$('body').delegate( '.pp-offcanvas-content .pp-offcanvas-body a', 'click', $.proxy( this.close, this ) );

            if ( this.esc_close === 'yes' ) {
                this.closeESC();
            }
            if ( this.body_click_close === 'yes' ) {
                this.closeClick();
            }
		},

		toggleContent: function(e) {
			e.preventDefault();

			if ( ! $('html').hasClass('pp-offcanvas-content-open') ) {
				this.show();
			} else {
				this.close();
			}
		},

		show: function() {
			$('.pp-offcanvas-content-' + this.id).addClass('pp-offcanvas-content-visible');
			// init animation class.
			$('html').addClass( 'pp-offcanvas-content-' + this.transition );
			$('html').addClass( 'pp-offcanvas-content-' + this.direction );
			$('html').addClass('pp-offcanvas-content-open');
			$('html').addClass('pp-offcanvas-content-' + this.id + '-open');
			$('html').addClass('pp-offcanvas-content-reset');
            
            this.button.addClass('pp-is-active');
		},

		close: function() {
			$('html').removeClass('pp-offcanvas-content-open');
			$('html').removeClass('pp-offcanvas-content-' + this.id + '-open');
			setTimeout($.proxy(function () {
				$('html').removeClass('pp-offcanvas-content-reset');
				$('html').removeClass( 'pp-offcanvas-content-' + this.transition );
                $('html').removeClass( 'pp-offcanvas-content-' + this.direction );
				$('.pp-offcanvas-content-' + this.id).removeClass('pp-offcanvas-content-visible');
			}, this), 500);
            
            this.button.removeClass('pp-is-active');
		},

		closeESC: function() {
			var self = this;

			if ( '' === self.settings.esc_close ) {
				return;
			}

			// menu close on ESC key
			$(document).on('keydown', function (e) {
				if (e.keyCode === 27) { // ESC
					self.close();
				}
			});
		},

		closeClick: function() {
			var self = this;
			
			if ( this.toggle_source == 'element-id' && this.toggle_id != '' ) {
				$trigger = '#' + this.toggle_id;
			} else if ( this.toggle_source == 'element-class' && this.toggle_class != '' ) {
				$trigger = '.' + this.toggle_class;
			} else {
				$trigger = '.pp-offcanvas-toggle';
			}

			$(document).on('click', function(e) {
				if ( $(e.target).is('.pp-offcanvas-content') || $(e.target).parents('.pp-offcanvas-content').length > 0 || $(e.target).is('.pp-offcanvas-toggle') || $(e.target).parents('.pp-offcanvas-toggle').length > 0 || $(e.target).is($trigger) || $(e.target).parents($trigger).length > 0 ) {
					return;
				} else {
					self.close();
				}
			});
		}
	};

})(jQuery);