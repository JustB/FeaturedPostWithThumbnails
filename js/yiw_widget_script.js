/*
 * @package Featured Posts
 * @author Nando Pappalardo e Giustino Borzacchiello
 * @version {{VERSIONE}}
 */
function hideShowCat($, $widgetForm) {
    'use strict';
	var $cat = $('.yiw_featured_post_category:first', $widgetForm),
		$show = $('.yiw_featured_post_show:first', $widgetForm),
		$selected = $('option:selected', $show);

	if ($selected.text().indexOf("Featured") > 0) {
        $cat.hide();
    }
    $show.change(function () {
		$('option:selected', $show).each(function () {
			if ($(this).text().indexOf("Category") > 0) { $cat.show(); } else { $cat.hide(); }
		});
	});
}

function updateCategorySelects($, $widgetForms) {
    'use strict';
    $widgetForms.each(function () {
        hideShowCat($, $(this));
    });
}

(function ($) {
    'use strict';
    $(function () {
        updateCategorySelects($, $('.yiw_featured_post_widget'));
    });

    $(document).ajaxSuccess(function () {
        updateCategorySelects($, $('.yiw_featured_post_widget'));
    });

}(jQuery));

