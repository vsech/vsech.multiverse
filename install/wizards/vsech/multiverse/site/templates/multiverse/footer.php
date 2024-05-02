<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>

				<!-- Footer -->
					<footer id="footer" class="panel">
						<div class="inner split">
							<div>
								<section>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/about.php"
	)
);?>
								</section>
								<section>
									<h2><?=GetMessage("FOOTER_SOCIAL");?></h2>
									<ul class="icons">
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/social.php"
	)
);?>
									</ul>
								</section>
								<p class="copyright">			
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/copyright.php"
	)
);?>
								</p>
							</div>
							<div>
								<section>
<?$APPLICATION->IncludeComponent("bitrix:main.feedback", "feedback", Array(
	"EMAIL_TO" => "web@vsech.tk",	// E-mail, на который будет отправлено письмо
		"EVENT_MESSAGE_ID" => array(	// Почтовые шаблоны для отправки письма
			0 => "82",
		),
		"OK_TEXT" => GetMessage("VSECH_MULTIVERSE_SPASIBO_VASE_SOOBSE"),	// Сообщение, выводимое пользователю после отправки
		"REQUIRED_FIELDS" => array(	// Обязательные поля для заполнения
			0 => "NAME",
			1 => "EMAIL",
			2 => "MESSAGE",
		),
		"USE_CAPTCHA" => "Y",	// Использовать защиту от автоматических сообщений (CAPTCHA) для неавторизованных пользователей
	),
	false
);?>
								</section>
							</div>
						</div>
					</footer>
			</div>
		<?//JS
			//CJSCore::Init(array("jquery2"));
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/js/jquery.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/js/jquery.poptrox.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/js/skel.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/js/util.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/js/main.js');
		?>
			<!--[if lte IE 8]><script src="<?=SITE_TEMPLATE_PATH?>/assets/js/ie/respond.min.js"></script><![endif]-->
	</body>
</html>