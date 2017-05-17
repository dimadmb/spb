$(document).ready(function(){
	
$(function () {
    
	console.log(location.hash);
	
	var tabContainers = $('div.tabs > div');
    tabContainers.hide().filter(':first').show();
    $('div.tabs ul.tabNavigation a').click(function () {
        tabContainers.hide();
        tabContainers.filter(this.hash).show();
        $('div.tabs ul.tabNavigation a').removeClass('selected');
        $(this).addClass('selected');
        return false;
    }).filter(':first').click();
	
	if(location.hash != "") 
	{
		$('div.tabs ul.tabNavigation a[href="' + location.hash + '"]').click();
	}
});

jQuery(function ($) {
        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
            dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
            dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            weekHeader: 'Нед',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ru']);
});

	var mtop=0;
	var maxscroll=0;
	$(window).scroll(function() {
		if($(this).scrollTop() > 200) {
			$('.right-form').addClass('fixed');
			maxscroll=$(document).height()-888;
			if($(this).scrollTop() > maxscroll) {
				mtop=maxscroll-$(this).scrollTop();
				$('.right-form').css("margin-top",mtop+"px");
			}
			else {
				$('.right-form').css("margin-top","0");
			}
		} else {
			$('.right-form').removeClass('fixed');
		}
	});
	$('.tabs .content .tab-zag').click(function() {
		$(this).toggleClass('active');
		$(this).next('.tabs .content .text').slideToggle();
	});
	
	

	
	$('#datepicker').datepicker({
		beforeShowDay: function(date){
			var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
			return [ arrayGlobalActiveDate.indexOf(string) > -1 ];
		}
	});	

	
	//$( "#datepicker" ).datepicker();
	//$("select").selectBox();

	//$("#direction").on("change", function() {console.log("Смена направления");})
	//$("#time1").on("change", function() {console.log("Смена отправления");})
	//$("#time2").on("change", function() {console.log("Смена возвращения");})
	//$("#num1").on("change", function() {console.log("Смена количества билетов");})
	//$("#num2").on("change", function() {console.log("Смена количества билетов");})
	//$("#num3").on("change", function() {console.log("Смена количества билетов");})
	
});