<?php foreach ($this->inputs_settings as $key => $value) : ?>

	<?php if ($key !== "--input-radius") : ?>
	
			<div class="oxygen-control-row">
				<div class='oxygen-control-wrapper' id='oxygen-typography-font-family'>
					<label class='oxygen-control-label'><?php echo $value; ?></label>
					<div class='oxygen-control'>

						<div class='oxygen-color-picker'>
							<div class="oxygen-color-picker-color">
								<input ctiriscolorpicker=""
									class="ct-iris-colorpicker"
									type="text" spellcheck="false"
									ng-model="$parent.iframeScope.globalSettings.woo['<?php echo $key; ?>']"
									ng-style="{'background-color':$parent.iframeScope.globalSettings.woo['<?php echo $key; ?>']}"/>
							</div>
							<input type="text" spellcheck="false"
								ng-model="$parent.iframeScope.globalSettings.woo['<?php echo $key; ?>']"/>
							<img class="oxygen-add-global-color-icon" 
								title="<?php _e("Save as Global Color","oxygen"); ?>"
								src='<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/make-global-color.svg'
								ng-click="$parent.showAddNewColorDialog($event)"/>
						</div>
					</div>
				</div>
			</div>

	<?php else : ?>

		<div class='oxygen-control-row'>
			<?php $oxygen_toolbar->global_measure_box_with_wrapper('global', "['woo']['$key']", $value, 'px'); ?>
		</div>

	<?php endif; ?>

<?php endforeach; ?>