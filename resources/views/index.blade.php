<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Тестовое задание</title>
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
</head>
<body>
<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">
            <h1 class="text-center">Задачи</h1>
            <div class="row">
                <div class="col-md-4">
                    <button type="button" class="btn btn-sm btn-info"
                            onclick="Controller.create(this)">Добавить задачу
                    </button>
                </div>
                <div class="col-md-8">
                    <button type="button" class="btn btn-sm btn-info float-right"
                            onclick="Controller.index()">Обновить список
                    </button>
                </div>
            </div>
            <hr>
            <div id="tasks-list" class="row"></div>
        </div>
    </div>
    <div class="modal fade" id="modalTask">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formTask">
                    <div class="modal-header">
                        <h5 class="modal-title">Задача</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Название:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Приоритет:</label>
                            <select class="form-control" name="status" required>
                                @foreach($pageSettings['statuses'] as $status)
                                    <option value="{{ $status }}" class="translate">__(statuses.{{ $status }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Статус:</label>
                            <select class="form-control" name="priority" required>
                                @foreach($pageSettings['priorities'] as $priority)
                                    <option value="{{ $priority }}" class="translate">__(priorities.{{ $priority }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Теги:</label>
                            <select class="form-control" style="width: 100%;" multiple="multiple" name="tags[]"
                                    required></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <input type="submit" class="btn btn-info btn-sbt" onsubmit="return false;" value="Отправить">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDeleteTask" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить задачу</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Отменить">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены что хотите удалить задачу?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button id="buttonDeleteTask" type="button" class="btn btn-danger"
                            onclick="Controller.destroy(this)">Удалить
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-none">
        <div id="card-task-template">
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <b class="template-title"></b>
                        <p class="card-text">
                            <small class="text-muted">
                                <span class="template-status translate"></span>
                                (<span class="template-priority translate"></span>)
                            </small>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="card-text template-tags">
                            <span class="badge badge-primary"></span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm"
                                        data-task-id=""
                                        onclick="Controller.edit(this)">Изменить
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                        data-task-id=""
                                        onclick="Controller.delete(this)">Удалить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    const NAME_ATTR_TASK_ID = 'data-task-id';
    const TASKS_LIST = '#tasks-list';
    const MODAL_TASK = '#modalTask';
    const MODAL_DELETE_TASK = '#modalDeleteTask';
    const FORM_TASK = '#formTask';

    var translator = {};
    translator.tasks = JSON.parse('{!! $translations !!}');

    var Helpers = {
        getIdFromObj(element) {
            let taskId = $(element).attr(NAME_ATTR_TASK_ID);

            if (jQuery.isEmptyObject(taskId)) {
                throw new Error('Ошибка. Не найден taskId');
            }

            return taskId;
        },
        getFromById(taskId) {
            var task = null;

            $.each(Controller.tasks, function (key, card) {
                if (card['id'] === taskId) {
                    task = card;
                    return false;
                }
            });

            return task;
        },
        setFormValues(taskId) {
            let task = Helpers.getFromById(taskId);

            $(FORM_TASK).find('input[name="name"]').val(task['name']);
            $(FORM_TASK).find('select[name="priority"]').val(task['priority']);
            $(FORM_TASK).find('select[name="status"]').val(task['status']);

            $.each(task['tags'], function (num, tag) {
                $(FORM_TASK)
                .find('select[name="tags[]"]')
                .append($('<option name="name" selected></option>').attr("value", tag['name']).text(tag['name']));
            });
            $(FORM_TASK).find('select[name="tags[]"]').trigger('change');
        },
        setSettingsInForm(title, textButton, onClick, dataTaskId = '') {
            $(MODAL_TASK).find('.modal-title').text(title);
            $(FORM_TASK).find('.btn-sbt').attr(NAME_ATTR_TASK_ID, dataTaskId).val(textButton);
            $(FORM_TASK).attr('onsubmit', onClick);
            $(FORM_TASK)[0].reset();
            $(FORM_TASK).find('select[name="tags[]"]').find('option').remove();
            $(FORM_TASK).find('select[name="tags[]"]').val(null).trigger('change');
        },
        trans(text) {
            if (jQuery.isEmptyObject(text)) {
                return;
            }

            let translateText = text.match(/__\((.*?)\)/);

            if (jQuery.isEmptyObject(translateText)) {
                return;
            }

            let newText = translator.tasks[translateText[1]];

            if (jQuery.isEmptyObject(newText)) {
                return;
            }

            return newText;
        },
        templateTranslation(htmlIdentifier) {
            $(htmlIdentifier).find('.translate').each(function (num, element) {
                let text = $(element).text();

                let newText = Helpers.trans(text);

                $(element).text(newText);
            });
        },
        getCard(card) {
            let cardTaskTempName = '#card-task-template';

            $(cardTaskTempName).find('.template-title').text(card['name']);
            $(cardTaskTempName).find('.template-status').text('__(statuses.' + card['status'] + ')');
            $(cardTaskTempName).find('.template-priority').html('__(priorities.' + card['priority'] + ')');

            let htmlTags = '';

            $.each(card['tags'], function (num, tag) {
                htmlTags += '<span class="badge badge-primary">' + tag['name'] + '</span> ';
            });

            $(cardTaskTempName).find('.template-tags').html(htmlTags);
            $(cardTaskTempName).find('[' + NAME_ATTR_TASK_ID + ']').attr(NAME_ATTR_TASK_ID, card['id']);

            Helpers.templateTranslation(cardTaskTempName);

            return $(cardTaskTempName).html();
        },
        sendRequest(method, url, data = []) {
            var isSuccessful = false;

            $.ajax({
                method: method,
                async: false,
                url: url,
                data: data,
                success: function () {
                    isSuccessful = true;
                }
            });

            if (!isSuccessful) {
                throw new Error('Произошла ошибка.');
            }
        }
    };

    var Controller = {
        tasks: [],

        __construct() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });

            Helpers.templateTranslation('html');

            $(FORM_TASK).find('select[name="tags[]"]').select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });

            Controller.index();
        },
        index() {
            $.getJSON({
                url: "/tasks/",
                async: false,
                success: function (data) {
                    Controller.tasks = data;
                }
            });

            var htmlTasks = '';

            if (jQuery.isEmptyObject(Controller.tasks)) {
                $(TASKS_LIST).html('');
                return;
            }

            $.each(Controller.tasks, function (key, card) {
                let cardHtml = Helpers.getCard(card);
                htmlTasks += cardHtml;
            });

            $(TASKS_LIST).html(htmlTasks);
        },
        create() {
            Helpers.setSettingsInForm('Добавить задачу', 'Создать', 'Controller.store(); return false;');

            $(MODAL_TASK).modal('show');
        },
        store() {
            try {
                let dataForm = $(FORM_TASK).serialize();

                Helpers.sendRequest('post', '/tasks', dataForm);

                $(MODAL_TASK).modal('hide');

                Controller.index();
            } catch (e) {
                alert(e.message);
            }
        },
        edit(element) {
            try {
                let taskId = Helpers.getIdFromObj(element);

                Helpers.setSettingsInForm('Изменить задачу', 'Сохранить', 'Controller.update(); return false;', taskId);
                Helpers.setFormValues(taskId);

                $(MODAL_TASK).modal('show');
            } catch (e) {
                alert(e.message);
            }
        },
        update() {
            try {
                let taskId = $(MODAL_TASK).find('.btn-sbt').attr(NAME_ATTR_TASK_ID);
                let dataForm = $(FORM_TASK).serialize();

                Helpers.sendRequest('put', '/tasks/' + taskId, dataForm);

                $(MODAL_TASK).modal('hide');

                Controller.index();
            } catch (e) {
                alert(e.message);
            }
        },
        delete(element) {
            try {
                let taskId = Helpers.getIdFromObj(element);

                $(MODAL_DELETE_TASK).find('#buttonDeleteTask').attr(NAME_ATTR_TASK_ID, taskId);
                $(MODAL_DELETE_TASK).modal('show');
            } catch (e) {
                alert(e.message);
            }
        },
        destroy(element) {
            try {
                let taskId = Helpers.getIdFromObj(element);

                Helpers.sendRequest('delete', '/tasks/' + taskId);

                $(MODAL_DELETE_TASK).modal('hide');

                Controller.index();
            } catch (e) {
                alert(e.message);
            }
        }
    };

    $(document).ready(function () {
        Controller.__construct();
    });
</script>
</body>
</html>
