import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Quote from '@editorjs/quote';
import RawTool from '@editorjs/raw';
import SimpleImage from "@editorjs/simple-image";
import EditorjsList from '@editorjs/list';
import Embed from '@editorjs/embed';
import Paragraph from '@editorjs/paragraph';
import Table from '@editorjs/table';
import CodeTool from '@editorjs/code';
import Underline from '@editorjs/underline';
import Delimiter from '@editorjs/delimiter';
import InlineCode from '@editorjs/inline-code';

const wrapper = document.getElementById('editorjs');
if (wrapper) {
  const input = document.getElementById(wrapper.dataset.fieldId);
  const editor = new EditorJS({
    holder: wrapper.id,
    tools: {
      header: {
        class: Header,
        shortcut: 'CMD+SHIFT+H',
        config: {
          levels: [2, 3, 4, 5, 6],
          defaultLevel: 3
        }
      },
      paragraph: {
        class: Paragraph,
        inlineToolbar: true,
        shortcut: 'CMD+SHIFT+P',
      },
      underline: {
        class: Underline,
        shortcut: 'CMD+SHIFT+U',
      },
      delimiter: {
        class: Delimiter,
        shortcut: 'CMD+SHIFT+D',
      },
      inlineCode: {
        class: InlineCode,
        shortcut: 'CMD+SHIFT+M',
      },
      list: {
        class: EditorjsList,
        inlineToolbar: true,
        shortcut: 'CMD+SHIFT+L',
        config: {
          defaultStyle: 'unordered'
        },
      },
      code: {
        class: CodeTool,
        shortcut: 'CMD+SHIFT+C',
      },
      embed: Embed,
      table: {
        class: Table,
        shortcut: 'CMD+SHIFT+T',
      },
      quote: {
        class: Quote,
        inlineToolbar: true,
        shortcut: 'CMD+SHIFT+Q',
      },
      raw: RawTool,
      image: SimpleImage,
    },
    onReady: () => {
      editor.render(JSON.parse(input.value));
    },
    onChange: (api, event) => {
      editor.save().then((outputData) => {
        input.value = JSON.stringify(outputData);
      });
    }
  });
}


