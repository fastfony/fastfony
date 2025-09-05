import { html as beautifyHtml } from 'js-beautify';
import Quill from 'quill';

const toolbarOptions = [
  [
    { header: '2' },
    { header: '3' },
    { header: '4' },
    { header: '5' },
    { header: '6' },
  ],
  [{ size: [] }],
  [{ align: [] }],
  ['bold', 'italic', 'underline', 'strike', 'blockquote'],
  [{ color: [] }, { background: [] }],
  ['blockquote', 'code-block'],
  [{ script: 'sub' }, { script: 'super' }],
  [
    { list: 'ordered' },
    { list: 'bullet' },
    { list: 'check' },
    { indent: '-1' },
    { indent: '+1' },
  ],
  ['link', 'image', 'video'],
  ['clean'],
];

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.rich-text-editor').forEach(function (textarea) {
    const quillContainer = document.createElement('div');
    quillContainer.style.minHeight = '300px';
    const quillWrapper = document.createElement('div');
    quillWrapper.appendChild(quillContainer);
    quillWrapper.classList.add('h-100');

    const quill = new Quill(quillContainer, {
      theme: 'snow',
      modules: {
        toolbar: toolbarOptions,
      },
    });

    quill.on('text-change', function (delta) {
      textarea.value = formatHtml(quill.root.innerHTML);
    });

    const formWrapper = document.createElement('div');
    textarea.parentNode.appendChild(formWrapper);
    formWrapper.appendChild(quillWrapper);
    formWrapper.appendChild(textarea);
    formWrapper.classList.add(
      'd-flex',
      'flex-column',
      'flex-lg-row',
      'gap-3',
      'my-3',
    );

    textarea.addEventListener('input', function () {
      // If user edit the HTML directly in the textarea, we disable Quill
      // (save changes is necessary)
      quill.enable(false);
      formWrapper.parentNode
        .querySelector('.form-help')
        .classList.add('text-danger', 'text-right', 'fw-bold');
    });

    quill.root.innerHTML = textarea.value;
  });
});

function formatHtml(html) {
  return beautifyHtml(html, {
    indent_size: 4,
    preserve_newlines: true,
  });
}

export default toolbarOptions;
