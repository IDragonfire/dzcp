<?php
	/**
	 * language pack
	 * @author Steffen Schalow (s@schallah.de)
	 * @link www.schallah.de
	 * @since 23/Feb/2010
	 *
	 */
	define('DATE_TIME_FORMAT', 'd.M.Y H:i:s');
	//Common
	//Menu
	
	
	
	
	define('MENU_SELECT', 'Auswählen');
	define('MENU_DOWNLOAD', 'Download');
	define('MENU_PREVIEW', 'Vorschau');
	define('MENU_RENAME', 'Umbenennen');
	define('MENU_EDIT', 'Bearbeiten');
	define('MENU_CUT', 'Ausschneiden');
	define('MENU_COPY', 'Kopieren');
	define('MENU_DELETE', 'Löschen');
	define('MENU_PLAY', 'Play');
	define('MENU_PASTE', 'Einfügen');
	
	//Label
		//Top Action
		define('LBL_ACTION_REFRESH', 'Aktualisieren');
		define('LBL_ACTION_DELETE', 'Löschen');
		define('LBL_ACTION_CUT', 'Ausschneiden');
		define('LBL_ACTION_COPY', 'Kopieren');
		define('LBL_ACTION_PASTE', 'Einfügen');
		define('LBL_ACTION_CLOSE', 'Schließen');
		define('LBL_ACTION_SELECT_ALL', 'Alles markieren');
		//File Listing
	define('LBL_NAME', 'Name');
	define('LBL_SIZE', 'Größe');
	define('LBL_MODIFIED', 'Bearbeitet');
		//File Information
	define('LBL_FILE_INFO', 'Datei Informationen:');
	define('LBL_FILE_NAME', 'Name:');	
	define('LBL_FILE_CREATED', 'Erstellt:');
	define('LBL_FILE_MODIFIED', 'Bearbeitet:');
	define('LBL_FILE_SIZE', 'Datei Größe:');
	define('LBL_FILE_TYPE', 'Datei Typ:');
	define('LBL_FILE_WRITABLE', 'Schreibbar?');
	define('LBL_FILE_READABLE', 'Lesbar?');
		//Folder Information
	define('LBL_FOLDER_INFO', 'Ordner Informationen');
	define('LBL_FOLDER_PATH', 'Ordner:');
	define('LBL_CURRENT_FOLDER_PATH', 'Aktueller Ordner Pfad:');
	define('LBL_FOLDER_CREATED', 'Erstellt:');
	define('LBL_FOLDER_MODIFIED', 'Bearbeitet:');
	define('LBL_FOLDER_SUDDIR', 'Unterordner:');
	define('LBL_FOLDER_FIELS', 'Dateien:');
	define('LBL_FOLDER_WRITABLE', 'Schreibbar?');
	define('LBL_FOLDER_READABLE', 'Lesbar?');
	define('LBL_FOLDER_ROOT', 'Root Ordner');
		//Preview
	define('LBL_PREVIEW', 'Vorschau');
	define('LBL_CLICK_PREVIEW', 'Hier klicken für eine Vorschau.');
	//Buttons
	define('LBL_BTN_SELECT', 'Auswählen');
	define('LBL_BTN_CANCEL', 'Abbrechen');
	define('LBL_BTN_UPLOAD', 'Hochladen');
	define('LBL_BTN_CREATE', 'Erstellen');
	define('LBL_BTN_CLOSE', 'Schließen');
	define('LBL_BTN_NEW_FOLDER', 'Neuer Ordner');
	define('LBL_BTN_NEW_FILE', 'Neue Datei');
	define('LBL_BTN_EDIT_IMAGE', 'Bearbeiten');
	define('LBL_BTN_VIEW', 'Markiere Ansicht');
	define('LBL_BTN_VIEW_TEXT', 'Text');
	define('LBL_BTN_VIEW_DETAILS', 'Details');
	define('LBL_BTN_VIEW_THUMBNAIL', 'Thumbnails');
	define('LBL_BTN_VIEW_OPTIONS', 'Ansicht in:');
	//pagination
	define('PAGINATION_NEXT', 'Vor');
	define('PAGINATION_PREVIOUS', 'Zurück');
	define('PAGINATION_LAST', 'Letztes');
	define('PAGINATION_FIRST', 'Erstes');
	define('PAGINATION_ITEMS_PER_PAGE', 'Zeige %s Elemente pro Seite');
	define('PAGINATION_GO_PARENT', 'Gehe eine Ebene höher');
	//System
	define('SYS_DISABLED', 'Zugriff verweigert: Das System ist deaktiviert.');
	
	//Cut
	define('ERR_NOT_DOC_SELECTED_FOR_CUT', 'Keine Datei(en)zum Ausschneiden ausgewählt.');
	//Copy
	define('ERR_NOT_DOC_SELECTED_FOR_COPY', 'Keine Datei(en) zum Kopieren ausgewählt.');
	//Paste
	define('ERR_NOT_DOC_SELECTED_FOR_PASTE', 'Keine Datei(en) zum Einfügen ausgewählt.');
	define('WARNING_CUT_PASTE', 'Wirklich die ausgewählten Dateien in diesen Ordner verschieben?');
	define('WARNING_COPY_PASTE', 'Wirklich die ausgewählten Dateien in diesen Ordner kopieren?');
	define('ERR_NOT_DEST_FOLDER_SPECIFIED', 'Kein Zielordner festgelegt.');
	define('ERR_DEST_FOLDER_NOT_FOUND', 'Zielordner nicht gefunden.');
	define('ERR_DEST_FOLDER_NOT_ALLOWED', 'Sie sind nicht berechtigt Dateien in diesen Ordner zu verschieben!');
	define('ERR_UNABLE_TO_MOVE_TO_SAME_DEST', 'Verschieben von (%s) fehlgeschlagen: Quellordner entspricht dem Zielordner.');
	define('ERR_UNABLE_TO_MOVE_NOT_FOUND', 'Verschieben von (%s) fehlgeschlagen: Datei existiert nicht.');
	define('ERR_UNABLE_TO_MOVE_NOT_ALLOWED', 'Verschieben von (%s) fehlgeschlagen: Zugriff auf Datei verweigert.');
 
	define('ERR_NOT_FILES_PASTED', 'Keine Datei(en) wurden eingefügt.');

	//Search
	define('LBL_SEARCH', 'Suchen');
	define('LBL_SEARCH_NAME', 'Dateiname oder Auszug:');
	define('LBL_SEARCH_FOLDER', 'Suche in:');
	define('LBL_SEARCH_QUICK', 'Schnell Suche');
	define('LBL_SEARCH_MTIME', 'Änderungsdatum (von - bis):');
	define('LBL_SEARCH_SIZE', 'Datei Größe:');
	define('LBL_SEARCH_ADV_OPTIONS', 'Erweiterte Optionen');
	define('LBL_SEARCH_FILE_TYPES', 'Datei Typen:');
	define('SEARCH_TYPE_EXE', 'Programm');
	
	define('SEARCH_TYPE_IMG', 'Bild');
	define('SEARCH_TYPE_ARCHIVE', 'Archiv');
	define('SEARCH_TYPE_HTML', 'HTML');
	define('SEARCH_TYPE_VIDEO', 'Video');
	define('SEARCH_TYPE_MOVIE', 'Movie');
	define('SEARCH_TYPE_MUSIC', 'Musik');
	define('SEARCH_TYPE_FLASH', 'Flash');
	define('SEARCH_TYPE_PPT', 'PowerPoint');
	define('SEARCH_TYPE_DOC', 'Dokument');
	define('SEARCH_TYPE_WORD', 'Word');
	define('SEARCH_TYPE_PDF', 'PDF');
	define('SEARCH_TYPE_EXCEL', 'Excel');
	define('SEARCH_TYPE_TEXT', 'Text');
	define('SEARCH_TYPE_UNKNOWN', 'Unbekannt');
	define('SEARCH_TYPE_XML', 'XML');
	define('SEARCH_ALL_FILE_TYPES', 'Alle Datei Typen');
	define('LBL_SEARCH_RECURSIVELY', 'Suche rekursiv:');
	define('LBL_RECURSIVELY_YES', 'Ja');
	define('LBL_RECURSIVELY_NO', 'Nein');
	define('BTN_SEARCH', 'Jetzt suchen');
	//thickbox
	define('THICKBOX_NEXT', 'Vor&gt;');
	define('THICKBOX_PREVIOUS', '&lt;Zurück');
	define('THICKBOX_CLOSE', 'Schließen');
	//Calendar
	define('CALENDAR_CLOSE', 'Schließen');
	define('CALENDAR_CLEAR', 'Leeren');
	define('CALENDAR_PREVIOUS', '&lt;Zurück');
	define('CALENDAR_NEXT', 'Vor&gt;');
	define('CALENDAR_CURRENT', 'Heute');
	define('CALENDAR_MON', 'Mo');
	define('CALENDAR_TUE', 'Di');
	define('CALENDAR_WED', 'Mi');
	define('CALENDAR_THU', 'Do');
	define('CALENDAR_FRI', 'Fr');
	define('CALENDAR_SAT', 'Sa');
	define('CALENDAR_SUN', 'So');
	define('CALENDAR_JAN', 'Jan');
	define('CALENDAR_FEB', 'Feb');
	define('CALENDAR_MAR', 'Mär');
	define('CALENDAR_APR', 'Apr');
	define('CALENDAR_MAY', 'Mai');
	define('CALENDAR_JUN', 'Jun');
	define('CALENDAR_JUL', 'Jul');
	define('CALENDAR_AUG', 'Aug');
	define('CALENDAR_SEP', 'Sep');
	define('CALENDAR_OCT', 'Okt');
	define('CALENDAR_NOV', 'Nov');
	define('CALENDAR_DEC', 'Dez');
	//ERROR MESSAGES
		//deletion
	define('ERR_NOT_FILE_SELECTED', 'Bitte eine Datei auswählen.');
	define('ERR_NOT_DOC_SELECTED', 'Keine Datei(en) zum löschen ausgewählt.');
	define('ERR_DELTED_FAILED', 'Löschen nicht möglich.');
	define('ERR_FOLDER_PATH_NOT_ALLOWED', 'Dieser Ordnerpfad ist nicht erlaubt.');
		//class manager
	define('ERR_FOLDER_NOT_FOUND', 'Folgender Ordner kann nicht gefunden werden: ');
		//rename
	define('ERR_RENAME_FORMAT', 'Bitte nur Buchstaben, Zahlen, Leerzeichen, Bindestriche und Unterstriche in Dateinamen verwenden.');
	define('ERR_RENAME_EXISTS', 'Name schon vorhanden.');
	define('ERR_RENAME_FILE_NOT_EXISTS', 'Die Datei/der Ordner existiert nicht.');
	define('ERR_RENAME_FAILED', 'Umbenennen nicht möglich, bitte erneut versuchen.');
	define('ERR_RENAME_EMPTY', 'Bitte einen Namen eingeben.');
	define('ERR_NO_CHANGES_MADE', 'Keine Änderungen wurden vorgenommen.');
	define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'Sie sind nicht berechtigt Dateien mit dieser Dateiendung umzubenennen.');
		//folder creation
	define('ERR_FOLDER_FORMAT', 'Bitte nur Buchstaben, Zahlen, Leerzeichen, Bindestriche und Unterstriche in Ordnernamen verwenden.');
	define('ERR_FOLDER_EXISTS', 'Ordnername schon vorhanden.');
	define('ERR_FOLDER_CREATION_FAILED', 'Ordner konnte nicht erstellt werden, bitte erneut versuchen.');
	define('ERR_FOLDER_NAME_EMPTY', 'Bitte einen Namen eingeben.');
	define('FOLDER_FORM_TITLE', 'Neuer Ordner');
	define('FOLDER_LBL_TITLE', 'Ordner Name:');
	define('FOLDER_LBL_CREATE', 'Erstellen');
	//New File
	define('NEW_FILE_FORM_TITLE', 'Neue Datei');
	define('NEW_FILE_LBL_TITLE', 'Dateiname:');
	define('NEW_FILE_CREATE', 'Erstellen');
		//file upload
	define('ERR_FILE_NAME_FORMAT', 'Bitte nur Buchstaben, Zahlen, Leerzeichen, Bindestriche und Unterstriche in Dateinamen verwenden.');
	define('ERR_FILE_NOT_UPLOADED', 'Keine Datei zum hochladen ausgewählt.');
	define('ERR_FILE_TYPE_NOT_ALLOWED', 'Diese Dateiendung ist nicht erlaubt.');
	define('ERR_FILE_MOVE_FAILED', 'Verschieben der Datei fehlgeschlagen.');
	define('ERR_FILE_NOT_AVAILABLE', 'Die Datei wurde nicht gefunden.');
	define('ERROR_FILE_TOO_BID', 'Datei zu groß (Max: %s).');
	define('FILE_FORM_TITLE', 'Datei Upload');
	define('FILE_LABEL_SELECT', 'Datei');
	define('FILE_LBL_MORE', 'Eine weiteres Uploadfeld hinzufügen');
	define('FILE_CANCEL_UPLOAD', 'Datei Upload abbrechen');
	define('FILE_LBL_UPLOAD', 'Hochladen');
	//file download
	define('ERR_DOWNLOAD_FILE_NOT_FOUND', 'Keine Datei zum herunterladen ausgewählt.');
	//Rename
	define('RENAME_FORM_TITLE', 'Umbenennen');
	define('RENAME_NEW_NAME', 'Neuer Name:');
	define('RENAME_LBL_RENAME', 'OK');

	//Tips
	define('TIP_FOLDER_GO_DOWN', 'Links klicken um in diesen Ordner zu kommen...');
	define('TIP_DOC_RENAME', 'Doppelklick für den Editiermodus...');
	define('TIP_FOLDER_GO_UP', 'Links klicken um eine Ebene höher zu gehen...');
	define('TIP_SELECT_ALL', 'Alles markieren');
	define('TIP_UNSELECT_ALL', 'Auswahl aufheben');
	//WARNING
	define('WARNING_DELETE', 'Wirklich die ausgewählten Datei(en) löschen?');
	define('WARNING_IMAGE_EDIT', 'Bitte ein Bild zum edieren auswählen.');
	define('WARNING_NOT_FILE_EDIT', 'Bitte eine Datei zum editieren auswählen.');
	define('WARING_WINDOW_CLOSE', 'Dieses Fenster wirklich schließen?');
	//Preview
	define('PREVIEW_NOT_PREVIEW', 'Keine Vorschau verfügbar.');
	define('PREVIEW_OPEN_FAILED', 'Datei konnte nicht geöffnet werden.');
	define('PREVIEW_IMAGE_LOAD_FAILED', 'Bild konnte nicht geladen werden.');

	//Login
	define('LOGIN_PAGE_TITLE', 'Ajax File Manager Login');
	define('LOGIN_FORM_TITLE', 'Login');
	define('LOGIN_USERNAME', 'Benutzername:');
	define('LOGIN_PASSWORD', 'Passwort:');
	define('LOGIN_FAILED', 'Falscher Benutzername oder falsches Passwort.');
	
	
	//88888888888   Below for Image Editor   888888888888888888888
		//Warning 
		define('IMG_WARNING_NO_CHANGE_BEFORE_SAVE', 'Sie haben noch keine Änderungen am Bild vorgenommen.');
		
		//General
		define('IMG_GEN_IMG_NOT_EXISTS', 'Bild existiert nicht');
		define('IMG_WARNING_LOST_CHANAGES', 'Ungespeicherte Änderungen gehen verloren, wirklich fortfahren?');
		define('IMG_WARNING_REST', 'Ungespeicherte Änderungen gehen verloren, wirklich zurücksetzen?');
		define('IMG_WARNING_EMPTY_RESET', 'Bisher wurden noch keine Änderungen vorgenommen');
		define('IMG_WARING_WIN_CLOSE', 'Dieses Fenster wirklich schließen?');
		define('IMG_WARNING_UNDO', 'Wirklich zurücksetzen und die Änderungen verwerfen?');
		define('IMG_WARING_FLIP_H', 'Wirklich horizontal spiegeln?');
		define('IMG_WARING_FLIP_V', 'Wirklich vertikal spiegeln');
		define('IMG_INFO', 'Bild Informationen');
		
		//Mode
			define('IMG_MODE_RESIZE', 'Größe ändern:');
			define('IMG_MODE_CROP', 'Zuschneiden:');
			define('IMG_MODE_ROTATE', 'Drehen:');
			define('IMG_MODE_FLIP', 'Spiegeln:');		
		//Button
		
			define('IMG_BTN_ROTATE_LEFT', '90°GUZ');
			define('IMG_BTN_ROTATE_RIGHT', '90°IUZ');
			define('IMG_BTN_FLIP_H', 'Horizontal spiegeln');
			define('IMG_BTN_FLIP_V', 'Vertikal spiegeln');
			define('IMG_BTN_RESET', 'Zurücksetzen');
			define('IMG_BTN_UNDO', 'Rückgängig');
			define('IMG_BTN_SAVE', 'Speichern');
			define('IMG_BTN_CLOSE', 'Schließen');
			define('IMG_BTN_SAVE_AS', 'Speichern unter');
			define('IMG_BTN_CANCEL', 'Abbrechen');
		//Checkbox
			define('IMG_CHECKBOX_CONSTRAINT', 'Proportionen beibehalten');
		//Label
			define('IMG_LBL_WIDTH', 'Breite:');
			define('IMG_LBL_HEIGHT', 'Höhe:');
			define('IMG_LBL_X', 'X:');
			define('IMG_LBL_Y', 'Y:');
			define('IMG_LBL_RATIO', 'Verhältnis:');
			define('IMG_LBL_ANGLE', 'Winkel:');
			define('IMG_LBL_NEW_NAME', 'Neuer Name:');
			define('IMG_LBL_SAVE_AS', 'Speichern unter...');
			define('IMG_LBL_SAVE_TO', 'Speichern in:');
			define('IMG_LBL_ROOT_FOLDER', 'Root Ordner');
		//Editor
		//Save as 
		define('IMG_NEW_NAME_COMMENTS', 'Bitte die Dateierweiterung weg lassen.');
		define('IMG_SAVE_AS_ERR_NAME_INVALID', 'Bitte nur Buchstaben, Zahlen, Leerzeichen, Bindestriche und Unterstriche in Dateinamen verwenden.');
		define('IMG_SAVE_AS_NOT_FOLDER_SELECTED', 'Kein Zielordner ausgewählt.');	
		define('IMG_SAVE_AS_FOLDER_NOT_FOUND', 'Der Zielordner existiert nicht.');
		define('IMG_SAVE_AS_NEW_IMAGE_EXISTS', 'Es existiert ein Bild mit dem gleichen Namen.');

		//Save
		define('IMG_SAVE_EMPTY_PATH', 'Leerer Bild Pfad.');
		define('IMG_SAVE_NOT_EXISTS', 'Bild exisiert nicht.');
		define('IMG_SAVE_PATH_DISALLOWED', 'Sie haben keinen Zugriff auf diese Datei.');
		define('IMG_SAVE_UNKNOWN_MODE', 'Unerwarteter Bild Operation Modus');
		define('IMG_SAVE_RESIZE_FAILED', 'Größe ändern fehlgeschlagen.');
		define('IMG_SAVE_CROP_FAILED', 'Zuschneiden fehlgeschlagen.');
		define('IMG_SAVE_FAILED', 'Bild konnte nicht gespeichert werden.');
		define('IMG_SAVE_BACKUP_FAILED', 'Das Originalbild konnte nicht gesichert werden.');
		define('IMG_SAVE_ROTATE_FAILED', 'Das Bild konnte nicht gedreht werden.');
		define('IMG_SAVE_FLIP_FAILED', 'Das Bild konnte nicht gespiegelt werden.');
		define('IMG_SAVE_SESSION_IMG_OPEN_FAILED', 'Das Bild kann aus der Sitzung nicht geöffnet werden.');
		define('IMG_SAVE_IMG_OPEN_FAILED', 'Das Bild kann nicht geöffnet werden.');
		
		
		//UNDO
		define('IMG_UNDO_NO_HISTORY_AVAIALBE', 'Kein Verlauf für vorhanden. Es kann nichts rückgängig gemacht werden.');
		define('IMG_UNDO_COPY_FAILED', 'Rückgängig nicht möglich.');
		define('IMG_UNDO_DEL_FAILED', 'Das Bild konnte nicht aus der Sitzung gelöscht werden.');
	
	//88888888888   Above for Image Editor   888888888888888888888
	
	//88888888888   Session   888888888888888888888
		define('SESSION_PERSONAL_DIR_NOT_FOUND', 'Unable to find the dedicated folder which should have been created under session folder');
		define('SESSION_COUNTER_FILE_CREATE_FAILED', 'Unable to open the session counter file.');
		define('SESSION_COUNTER_FILE_WRITE_FAILED', 'Unable to write the session counter file.');
	//88888888888   Session   888888888888888888888
	
	//88888888888   Below for Text Editor   888888888888888888888
		define('TXT_FILE_NOT_FOUND', 'Datei nicht gefunden.');
		define('TXT_EXT_NOT_SELECTED', 'Bitte Dateiendung wählen');
		define('TXT_DEST_FOLDER_NOT_SELECTED', 'Bitte Zielordner wählen');
		define('TXT_UNKNOWN_REQUEST', 'Unbekannte Abfrage.');
		define('TXT_DISALLOWED_EXT', 'Sie können diese Dateitypen editieren/hinzufügen.');
		define('TXT_FILE_EXIST', 'Diese Datei existiert bereits.');
		define('TXT_FILE_NOT_EXIST', 'Datei existiert nicht.');
		define('TXT_CREATE_FAILED', 'Erstellen einer neuen Datei fehlgeschlagen.');
		define('TXT_CONTENT_WRITE_FAILED', 'Inhalt in die Datei schreiben fehlgeschlagen.');
		define('TXT_FILE_OPEN_FAILED', 'Datei konnte nicht geöffnet werden.');
		define('TXT_CONTENT_UPDATE_FAILED', 'Inhalt konnte nicht aktualisiert werden.');
		define('TXT_SAVE_AS_ERR_NAME_INVALID', 'Bitte nur Buchstaben, Zahlen, Leerzeichen, Bindestriche und Unterstriche in Dateinamen verwenden.');
	//88888888888   Above for Text Editor   888888888888888888888
	
	
?>