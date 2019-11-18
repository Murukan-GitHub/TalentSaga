/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
/*
CKEDITOR.editorConfig = function (config) {
    config.toolbarGroups = [
		{ name: 'document', groups: ['mode', 'document', 'doctools'] },
		{ name: 'styles', groups: ['styles'] },
		{ name: 'clipboard', groups: ['clipboard', 'undo'] },
		{ name: 'colors', groups: ['colors'] },
		{ name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
		{ name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'] },
		{ name: 'insert', groups: ['insert'] },
		{ name: 'links', groups: ['links'] },
		{ name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
		{ name: 'forms', groups: ['forms'] },
		'/',
		'/',
		{ name: 'tools', groups: ['tools'] },
		{ name: 'others', groups: ['others'] },
		{ name: 'about', groups: ['about'] }
    ];

    config.removeButtons = 'Templates,Save,NewPage,Preview,Print,PasteFromWord,Find,Replace,Scayt,SelectAll,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,BidiLtr,BidiRtl,Language,CreateDiv,Subscript,Superscript,RemoveFormat,Strike,Flash,PageBreak,Iframe,HorizontalRule,About,Maximize,BGColor,ShowBlocks,Format,Font,FontSize,Undo,Redo,Copy,Cut,Paste,PasteText,Source';
};
*/

CKEDITOR.editorConfig = function( config ) {
	
	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.
	config.plugins = 'dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,div,resize,toolbar,elementspath,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,undo,wsc,youtube';
	config.skin = 'moono';
	// %REMOVE_END%

	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	// 'Templates,Preview,Print,PasteFromWord,Find,Replace,SelectAll,BidiLtr,BidiRtl,CreateDiv,Subscript,Superscript,RemoveFormat,Strike,Flash,PageBreak,Iframe,HorizontalRule,Maximize,BGColor,ShowBlocks,Format,Font,FontSize,Undo,Redo,Copy,Cut,Paste,PasteText,Source';
    config.removeButtons = 'NewPage,Save,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,About,Language';
};
