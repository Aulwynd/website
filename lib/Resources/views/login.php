<?
namespace Destiny;
use Destiny\Common\Utils\Tpl;
?>
<!DOCTYPE html>
<html>
<head>
<title><?=Tpl::title($model->title)?></title>
<meta charset="utf-8">
<?php include Tpl::file('seg/commontop.php') ?>
<?php include Tpl::file('seg/google.tracker.php') ?>
</head>
<body id="login">
	<?php include Tpl::file('seg/top.php') ?>
	
	<section class="container">
	
		<h1 class="title">
			<span>Login</span>
			<small>with your preferred login method</small>
		</h1>
		<hr size="1">
		
		<?php if(!empty($model->error)): ?>
		<div class="alert alert-error">
			<strong>Error!</strong>
			<?=Tpl::out($model->error->getMessage())?>
		</div>
		<?php endif; ?>
		
		<div class="content content-dark clearfix">
			<div class="control-group">
				<p>No private information will ever be shown on the website. This excludes the custom destiny.gg username you specify.</p>
				<span class="label label-inverse">Important!</span> Each login method will create a new user account <em>if they are not connected</em>.
				<br>To connect your accounts, use the method you first logged in with (twitch), and connect your other accounts within your profile.
			</div>
			<form id="loginForm" action="/login" method="post" style="margin:20px 0 0 0;">
				<input type="hidden" name="follow" value="<?=Tpl::out($model->follow)?>" />
				<div class="control-group">
					<div class="controls">
						<label class="checkbox">
							<input type="checkbox" name="rememberme" <?=($model->rememberme) ? 'checked':''?>> Remember my login
						</label>
						<span class="help-block">(this should only be used if you are on a private computer)</span>
					</div>
				</div>
				
				<div class="control-group">
					<label class="radio">
						<input type="radio" name="authProvider" value="twitch">
						<i class="icon-twitch"></i> Login with twitch
					</label>
				</div>
				<div class="control-group">
					<label class="radio">
						<input type="radio" name="authProvider" value="google">
						<i class="icon-google"></i> Login with google
					</label>
				</div>
				<div class="control-group">
					<label class="radio">
						<input type="radio" name="authProvider" value="twitter">
						<i class="icon-twitter"></i> Login with twitter
					</label>
				</div>
				
				<div class="form-actions" style="margin-bottom:0; border-radius:0 0 4px 4px;">
					<button type="submit" class="btn btn-primary btn-large">Continue</button>
				</div>
			</form>
		</div>
		
	</section>
	
	<?php include Tpl::file('seg/foot.php') ?>
	<?php include Tpl::file('seg/commonbottom.php') ?>
	
</body>
</html>