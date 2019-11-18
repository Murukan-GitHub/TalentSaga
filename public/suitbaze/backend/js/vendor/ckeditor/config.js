/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

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