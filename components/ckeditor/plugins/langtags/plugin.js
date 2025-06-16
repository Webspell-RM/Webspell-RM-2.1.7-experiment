CKEDITOR.plugins.add('langtags', {
    icons: 'de,en,it',
    init: function(editor) {
        editor.ui.addButton('LangDE', {
            label: 'Inserisci [[lang:de]]',
            command: 'insertDE',
            toolbar: 'insert',
            icon: this.path + 'icons/de.png'  
        });
        editor.addCommand('insertDE', {
            exec: function(editor) { editor.insertText('[[lang:de]]'); }
        });

        editor.ui.addButton('LangEN', {
            label: 'Inserisci [[lang:en]]',
            command: 'insertEN',
            toolbar: 'insert',
            icon: this.path + 'icons/en.png'
        });
        editor.addCommand('insertEN', {
            exec: function(editor) { editor.insertText('[[lang:en]]'); }
        });

        editor.ui.addButton('LangIT', {
            label: 'Inserisci [[lang:it]]',
            command: 'insertIT',
            toolbar: 'insert',
            icon: this.path + 'icons/it.png'
        });
        editor.addCommand('insertIT', {
            exec: function(editor) { editor.insertText('[[lang:it]]'); }
        });
    }
});
