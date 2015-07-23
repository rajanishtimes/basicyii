<?php
return [
    'ckeditor' => [
        'default' => [
            'height' => 350,
            'format_tags' => 'p;h3;h4;h5;h6;pre;div',
            //'plugins' => 'about,a11yhelp,basicstyles,bidi,blockquote,clipboard,colorbutton,colordialog,contextmenu,dialogadvtab,div,elementspath,enterkey,entities,filebrowser,find,flash,floatingspace,font,format,forms,horizontalrule,htmlwriter,image,iframe,indentlist,indentblock,justify,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastefromword,pastetext,preview,print,removeformat,resize,save,scayt,selectall,showblocks,showborders,smiley,sourcearea,specialchar,stylescombo,tab,table,tabletools,templates,toolbar,undo,wsc,wysiwygarea',
            'plugins' => 'about,a11yhelp,basicstyles,bidi,blockquote,clipboard,colorbutton,colordialog,contextmenu,dialogadvtab,div,elementspath,enterkey,entities,filebrowser,find,flash,floatingspace,format,forms,horizontalrule,htmlwriter,image,iframe,indentlist,indentblock,justify,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastefromword,pastetext,preview,print,removeformat,resize,save,scayt,selectall,showblocks,showborders,smiley,sourcearea,specialchar,tab,table,tabletools,templates,toolbar,undo,wsc,wysiwygarea',
            //'plugins' => 'font,format,image,justify,indentlist,indentblock,link,list,liststyle,pagebreak,pastefromword,pastetext,preview,removeformat,resize,wysiwygarea',
            'toolbarGroups' => [
                    ['name' => 'clipboard', 'groups' => ['mode','undo', 'selection', 'clipboard','doctools']],
                    ['name' => 'editing', 'groups' => ['tools', 'about']],
                    '/',
                    ['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
                    ['name' => 'insert'],
                    '/',
                    ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
                    ['name' => 'styles','groups'=>['styles']],
                    ['name' => 'colors'],
                    ['name' => 'links'],
                    ['name' => 'others'],
            ],
            'removeButtons' => 'Smiley,Iframe'
        ]
    ]
];
