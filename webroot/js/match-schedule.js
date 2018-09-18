$(function () {
	// プルダウン変更時に遷移
	$('select[name=Season]').change(function() {
		if ($(this).val() != '') {
			window.location.href = $(this).val();
		}
	});
	/*
	// ボタンを押下時に遷移
	$('#season').click(function() {
		if ($(this).val() != '') {
			window.location.href = $('select[name=pulldown2]').val();
		}
	});
	*/
});