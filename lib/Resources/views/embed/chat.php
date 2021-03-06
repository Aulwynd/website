<?php
use Destiny\Common\Utils\Tpl;
use Destiny\Common\User\UserRole;
use Destiny\Common\User\UserFeature;
use Destiny\Common\Session;
use Destiny\Common\Config;
?>
<!DOCTYPE html>
<html>
<head>
<title><?=Tpl::title($model->title)?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" charset="utf-8">
<?php include Tpl::file('seg/commontop.php') ?>
<link href="<?=Config::cdnv()?>/chat/css/style.min.css" rel="stylesheet" media="screen">
<?php include Tpl::file('seg/google.tracker.php') ?>
</head>
<body id="chat-embedded">

<div id="destinychat" class="chat chat-theme-dark chat-icons">

	<div class="chat-output-frame">
		<div class="chat-output nano">
			<div class="chat-lines overthrow"></div>
		</div>
	</div>
	
	<form class="chat-input">
		<div class="clearfix">
			<div class="chat-input-wrap">
				<div class="chat-input-control">
				<?php if(Session::hasRole(UserRole::USER)): ?>
				<input type="text" placeholder="Enter a message..." class="input" autocomplete="off" spellcheck="true"/>
				<?php else: ?>
				<a class="chat-login-msg" href="/login" target="_parent">You must be logged in to chat</a>
				<input type="hidden" class="input" />
				<?php endif; ?>
				</div>
			</div>
			<div class="chat-tools-wrap">
				<a class="iconbtn chat-send-btn" title="Send"><i class="icon-bullhorn icon-white subtle"></i></a>
				<a class="iconbtn chat-settings-btn" title="Settings"><i class="icon-cog icon-white subtle"></i></a>
				<a class="iconbtn chat-users-btn" title="Users"><i class="icon-user icon-white subtle"></i></a>
			</div>
		</div>
	</form>
	
	<div id="chat-user-list" class="chat-menu" style="visibility: hidden;">
		<div class="list-wrap clearfix">
			<div class="toolbar">
				<h5>
					<a href="#" class="close pull-right"><i class="icon-remove icon-white"></i></a>
					Users (~<span></span>)
				</h5>
			</div>
			<div id="chat-groups" class="scrollable nano">
				<div class="content">
					<h6>Admins</h6>
					<ul class="unstyled admins"></ul>
					<hr/>
					<h6>VIP</h6>
					<ul class="unstyled vips"></ul>
					<hr/>
					<h6>Moderators</h6>
					<ul class="unstyled moderators"></ul>
					<hr/>
					<h6>Subscribers</h6>
					<ul class="unstyled subs"></ul>
					<hr/>
					<h6>Plebs</h6>
					<ul class="unstyled plebs"></ul>
					<hr/>
					<h6>Bots</h6>
					<ul class="unstyled bots"></ul>
				</div>
			</div>
		</div>
	</div>
	
	<div id="chat-settings" class="chat-menu" style="visibility: hidden;">
		<div class="list-wrap clearfix">
			<div class="toolbar">
				<h5>
					<a href="#" class="close pull-right"><i class="icon-remove icon-white"></i></a>
					<span>Settings</span>
				</h5>
			</div>
			<ul class="unstyled">
				<li>
					<label class="checkbox" title="Show all user flair icons">
						<input name="hideflairicons" type="checkbox" /> Hide flair icons
					</label>
				</li>
				<li>
					<label class="checkbox" title="Show the timestamps next to the messages">
						<input name="showtime" type="checkbox" /> Show time for messages
					</label>
				</li>
				<li>
					<label class="checkbox" title="Highlight text that you are mentioned in">
						<input name="highlight" type="checkbox" checked="checked"/> Highlight on mention
					</label>
				</li>
				<li>
					<label class="text" title="Your custom list of words that will make messages highlight">
						Custom highlight words.
						<input name="customhighlight" type="text" placeholder="Separated using a comma (,)" style="font-size:0.9em;"/>
					</label>
				</li>
				<li>
					<label class="checkbox" title="Show desktop notifications on hightlight">
						<input name="notifications" type="checkbox" /> Desktop notification on highlight
					</label>
				</li>
				<li>
					<hr style="margin:5px 0;">
					See the <a href="/chat/faq" target="_blank">chat FAQ</a> for more information
				</li>
			</ul>
		</div>
	</div>
	
	<div class="user-tools" style="visibility: hidden;">
		<div class="wrap clearfix">
			<h5>
				<a href="#" class="close pull-right"><i class="icon-remove icon-white"></i></a>
				<span class="user-tools-user"></span>
			</h5>
			<div class="tools">
				<div class="user-tools-wrap">
					<a id="ignoreuser" href="#ignore"><i class="icon-eye-close icon-white"></i> Ignore</a>
					<a id="unignoreuser" href="#unignore"><i class="icon-eye-open icon-white"></i> Unignore</a>
					<?php if(Session::hasFeature(UserFeature::MODERATOR) || Session::hasFeature(UserFeature::ADMIN)): ?>
					<a id="toggle-mute-form" href="#mute" onclick="$('#user-mute-form').toggle(); $('#user-ban-form').hide();"><i class="icon-ban-circle icon-white"></i> Mute</a> 
					<a id="toggle-mute-form" href="#mute" onclick="$('#user-ban-form').toggle(); $('#user-mute-form').hide();"><i class="icon-remove icon-white"></i> Ban</a> 
					<a id="clearmessages" href="#clearmessages"><i class="icon-fire icon-white"></i> Clear messages</a> 
					<?php endif; ?>
				</div>
				<?php if(Session::hasFeature(UserFeature::MODERATOR) || Session::hasFeature(UserFeature::ADMIN)): ?>
				<form class="ban-form" id="user-mute-form" style="margin-top:10px; display: none;">
					<div>
						<select id="banTimeLength" class="select" style="width:150px;" onchange="$('#banReason').focus();">
							<option value="0">Length of time</option>
							<option value="10">10 minutes</option>
							<option value="30">30 minutes</option>
							<option value="60">1 hr</option>
							<option value="720">12 hrs</option>
							<option value="1440">24 hrs</option>
						</select>
					</div>
					<div>
						<button type="submit" class="btn btn-mini btn-primary">Confirm</button>
						<button type="button" class="btn btn-mini" onclick="$('.ban-form').hide();">Cancel</button>
					</div>
				</form>
				<form class="ban-form" id="user-ban-form" style="margin-top:10px; display: none;">
					<input type="hidden" name="ipBan" id="ipBan" value="" />
					<div>
						<select id="banTimeLength" class="select" style="width:150px;" onchange="$('#banReason').focus();">
							<option value="0">Length of time</option>
							<option value="1">1 minute</option>
							<option value="5">5 minutes</option>
							<option value="10">10 minutes</option>
							<option value="30">30 minutes</option>
							<option value="60">1 hr</option>
							<option value="720">12 hrs</option>
							<option value="1440">24 hrs</option>
							<option value="perm">Permanent</option>
						</select>
						<input type="text" class="input" id="banReason" placeholder="Reason for ban" />
					</div>
					<div>
						<button type="submit" class="btn btn-mini btn-primary">Ban user</button>
						<button type="submit" class="btn btn-mini btn-danger" onclick="$('#ipBan').val('1');">IP ban user</button>
						<button type="button" class="btn btn-mini" onclick="$('.ban-form').hide();">Cancel</button>
					</div>
				</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="hint-popup" style="visibility: hidden;">
		<div class="wrap clearfix">
			<div class="alert">
				<a class="hidehint" title="Hide hint"><i class="icon-remove subtle"></i></a>
				<a class="nexthint" title="Next hint"><i class="icon-chevron-right subtle"></i></a>
				<strong>Hint:</strong> <span class="hint-message"></span>
			</div>
		</div>
	</div>
	
</div>

<?php include Tpl::file('seg/commonbottom.php') ?>

<script src="/chat/history"></script>
<script src="<?=Config::cdn()?>/vendor/jquery.mousewheel/jquery.mousewheel.min.js"></script>
<script src="<?=Config::cdn()?>/vendor/overthrow/overthrow.min.js"></script>
<script src="<?=Config::cdn()?>/vendor/nanoscrollerjs/jquery.nanoscroller.js"></script>
<script src="<?=Config::cdnv()?>/chat/js/chat.min.js"></script>
<script>$('#destinychat').ChatGui(<?=Tpl::jsout($model->user)?>,<?=Tpl::jsout($model->options)?>);</script>
</body>
</html>
