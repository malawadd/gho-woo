
jQuery(document).ready(function ($) {
    var url = window.location.href;
    if (url.includes('page=cpmw-metamask-settings')) {
        $('[href="admin.php?page=cpmw-metamask-settings"]').parent('li').addClass('current');
        const selectElement = document.querySelector('select[name="cpmw_settings[Chain_network]"]');
        const optionToDisableFrom = selectElement.querySelector('option[value="0x61"]');
        let option = optionToDisableFrom.nextElementSibling;

        while (option) {
            option.disabled = true;
            option = option.nextElementSibling;
        }
    }

  


});


