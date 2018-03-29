$(document).ready(function () {
	var state = 1;

	$('.play_btn').css('background-size', '100% auto');

	$('.play_btn').on('click', function () {

		$('.play_btn').css('background', 'url(./img/lesson/play_btn.png)');
		$('.play_btn').css('background-size', '100% auto');
		$('.play_btn').each(function () {
			$(this).children('audio')[0].pause();
		});

		if(state == 1){
			$(this).css('background', 'url(./img/lesson/pause_btn.png)');
			$(this).css('background-size', '100% auto');
			$(this).children('audio')[0].play();
			state = 0
		}else{
			$(this).css('background', 'url(./img/lesson/play_btn.png)');
			$(this).css('background-size', '100% auto');
			$(this).children('audio')[0].pause();
			state = 1;
		}
	});
})