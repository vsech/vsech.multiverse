<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<?$APPLICATION->ShowMeta("robots")?>
		<?$APPLICATION->ShowMeta("keywords")?>
		<?$APPLICATION->ShowMeta("description")?>
		<title><?$APPLICATION->ShowTitle()?></title>
		<?$APPLICATION->ShowHead();
		IncludeTemplateLangFile(__FILE__);?>
		<?//CSS 
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/assets/css/main.css');
		?>
		<!--[if lte IE 8]><script src="<?=SITE_TEMPLATE_PATH?>/assets/js/ie/html5shiv.js"></script><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/ie8.css" /><![endif]-->
	</head>
<body>	
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Header -->
					<header id="header">
						<h1><a href="/"><strong><?$APPLICATION->ShowTitle(false)?></strong></a></h1>
						<nav>
							<ul>
								<li><a href="#footer" class="icon fa-info-circle"><?=GetMessage("ABOUT");?></a></li>
							</ul>
						</nav>
					</header>