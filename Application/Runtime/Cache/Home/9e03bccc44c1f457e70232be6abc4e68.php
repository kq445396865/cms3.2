<?php if (!defined('THINK_PATH')) exit();?>
	<section>
		<div class="container" style="text-align:center;">
			<h1 style="color:red"><?php echo ($message); ?></h1>
			<h3 id="location" >系统将在<span style="color:red">3</span>秒后自动跳转到首页</h3>
		</div>
	</section>
</body>
<script src="/Public/js/jquery.js"></script>
<script>
  //首页
  var url = "/";
  var time = 3;
  setInterval("refer()",1000);
  function refer() {
	if(time==0) {
	  location.href=url;
	}
	$("#location span").html(time);
	time--;
  }
</script>
</html>