//全选的实现
$(function() {
	$(".check-all").click(function() {
		$(".ids").prop("checked", this.checked);
	});
	$(".ids").click(function() {
		var option = $(".ids");
		option.each(function(i) {
			if(!this.checked) {
				$(".check-all").prop("checked", false);
				return false;
			} else {
				$(".check-all").prop("checked", true);
			}
		});
	});
})