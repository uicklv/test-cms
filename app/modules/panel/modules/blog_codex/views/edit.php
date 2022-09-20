<form id="form_box" action="{URL:panel/blog_codex/edit/<?= $this->blog->id; ?>}" method="post" enctype="multipart/form-data">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <!-- Title ROW -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow widget-top">
                    <div class="widget-header">

                        <div class="items_group items_group-wrap">
                            <div class="items_left-side">
                                <div class="title-block">
                                    <a class="btn-ellipse bs-tooltip fas fa-rss" href="{URL:panel/blog}"></a>
                                    <h1 class="page_title"><?= $this->blog->title ?></h1>
                                </div>
                            </div>

                            <div class="items_right-side">
                                <div class="items_small-block">
                                    <a href="{URL:blog/<?= $this->blog->slug; ?>}" class="btn-rectangle bs-tooltip fa fa-eye" title="View Blog" target="_blank"></a>

                                    <div class="social-btns-list">
                                        <a onclick="share_linkedin(this);" class="btn-social" href="#" data-url="{URL:blog/<?= $this->blog->slug; ?>}">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                        <a onclick="share_facebook(this);" class="btn-social" href="#" data-url="{URL:blog/<?= $this->blog->slug; ?>}">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                        <a onclick="share_twitter(this);" class="btn-social" href="#" data-url="{URL:blog/<?= $this->blog->slug; ?>}">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                        <a class="btn-social copy_btn" href="#" data-clipboard-text="{URL:blog/<?= $this->blog->slug; ?>}">
                                            <i class="fa fa-copy"></i>
                                        </a>
                                    </div>
                                </div>

                                <a class="btn btn-outline-warning" href="{URL:panel/blog_codex}"><i class="fas fa-reply"></i>Back</a>
                            </div>
                        </div>

                        <div class="items_group items_group-wrap items_group-bottom">
                            <div class="items_left-side">
                                <div class="option-btns-list scroll-list">
                                    <a class="btn btn-rectangle_medium active"><i class="bs-tooltip fa fa-pencil-alt"></i>Edit</a>
                                    <a href="{URL:panel/blog_codex/statistic/<?= $this->blog->id; ?>}" class="btn btn-rectangle_medium" title="Statistic"><i class="bs-tooltip fa fa-chart-bar"></i>Statistic</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>General</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label for="title">Blog Title</label>
                                <input type="text" class="form-control" name="title" id="title" value="<?= post('title', false, $this->blog->title); ?>">
                            </div>
                            <div class="form-group">
                                <label for="sector">Category</label>
                                <select class="form-control" name="sector" id="sector" required>
                                    <?php if (isset($this->sectors) && is_array($this->sectors) && count($this->sectors) > 0) { ?>
                                        <?php foreach ($this->sectors as $item) { ?>
                                            <option value="<?= $item->id; ?>" <?= checkOptionValue(post('function_ids'), $item->id, $this->blog->sector); ?>><?= $item->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <!-- Image -->
                            <label for="image">Image<small><i>(Image files must be under <?= file_upload_max_size_format() ?>, and JPG, GIF or PNG format)</i></small></label>
                            <div class="choose-file modern">
                                <input type="hidden" name="image" id="image" value="<?= post('image', false, $this->blog->image); ?>">
                                <a class="file-fake" onclick="load('panel/select_image', 'field=#image', 'width=600', 'height=400')" style="cursor: pointer;"><i class="fas fa-folder-open"></i>Choose image</a>

                                <div id="preview_image" class="preview_image">
                                    <img src="<?= _SITEDIR_ ?>data/blog/<?= post('image', false, $this->blog->image); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <label for="time">Date Published</label>
                            <input class="form-control" type="text" name="time" id="time" value="<?= post('time', false, date("d/m/Y", $this->blog->time ? $this->blog->time : time())); ?>">
                        </div>
                        <div class="form-group col-md-6 mb-0">
                            <label for="posted">Published</label>
                            <select class="form-control" name="posted" id="posted" required>
                                <option value="yes" <?= checkOptionValue(post('posted'), 'yes', $this->blog->posted); ?>>Yes</option>
                                <option value="no" <?= checkOptionValue(post('posted'), 'no', $this->blog->posted); ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Content</h4>
                    <div class="form-group mb-0">
                        <div id="editorjs"></div>
                        <input type="hidden" name="content" id="content_field" value="">
                    </div>
                </div>
            </div>

            <!-- Author -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>Author</h4>
                    <div class="form-group mb-0">
                        <select class="form-control" name="consultant_id" id="consultant_id" required>
                            <?php if (isset($this->team) && is_array($this->team) && count($this->team) > 0) { ?>
                                <?php foreach ($this->team as $member) { ?>
                                    <?php if (!$this->blog->consultant_id) { ?>
                                        <option value="<?= $member->id; ?>" <?= checkOptionValue(post('consultant_id'), $member->id, User::get('id')); ?>><?= $member->firstname . ' ' . $member->lastname; ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $member->id; ?>" <?= checkOptionValue(post('consultant_id'), $member->id, $this->blog->consultant_id); ?>><?= $member->firstname . ' ' . $member->lastname; ?></option>
                                    <?php }
                                } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <h4>On-page SEO</h4>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="meta_title">
                                Meta Title<a href="https://moz.com/learn/seo/title-tag" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_title" id="meta_title" value="<?= post('meta_title', false, $this->blog->meta_title); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="meta_keywords">
                                Meta Keywords<a href="https://moz.com/learn/seo/what-are-keywords" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" value="<?= post('meta_keywords', false, $this->blog->meta_keywords); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-0">
                            <label for="meta_desc">
                                Meta Description<a href="https://moz.com/learn/seo/meta-description" target="_blank"><i class="fas fa-info-circle"></i></a>
                            </label>
                            <input class="form-control" type="text" name="meta_desc" id="meta_desc" value="<?= post('meta_desc', false, $this->blog->meta_desc); ?>">
                        </div>
                        <div class="form-group col-md-6 mb-0">
                            <label for="slug">
                                URL Slug<a href="https://moz.com/blog_codex/15-seo-best-practices-for-structuring-urls" target="_blank"><i class="fas fa-info-circle"></i></a>
                                &nbsp;&nbsp;{URL:blog}/<?= $this->blog->slug; ?>
                            </label>
                            <input class="form-control" type="text" name="slug" id="slug" value="<?= $this->blog->slug; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Buttons -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing sticky-block-bottom">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header widget-bottom">
                        <a class="btn btn-outline-warning" href="{URL:panel/blog_codex}"><i class="fas fa-reply"></i>Back</a>
                        <a class="btn btn-success" onclick="setTextareaValue();">
                            <i class="fas fa-save"></i>Save Changes
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<!-- Load Editor.js's Core -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>

<script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script><!-- Header -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script><!-- Delimiter -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script><!-- List -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script><!-- Checklist -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script><!-- Quote -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script><!-- Code -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script><!-- Embed -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script><!-- Table -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script><!-- Link -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script><!-- Warning -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script><!-- Marker -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script><!-- Inline Code -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/underline@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/personality@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/nested-list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/editorjs-drag-drop"></script>

<!-- Connect editor -->
<script>
    var content = <?= trim($this->blog->content) ?: '{}' ?>;

    if (!isEmpty(content)) {
        content.blocks.forEach((element) => {
            if (element.type == 'raw') {
                element.data.html = atob(element.data.html);
            }

            if (element.type == 'code') {
                element.data.code = atob(element.data.code);
            }
        });
    }

    /**
     * To initialize the Editor, create a new instance with configuration object
     * @see docs/installation.md for mode details
     */
    var editor = new EditorJS({
        /**
         * Enable/Disable the read only mode
         */
        readOnly: false,

        /**
         * Wrapper of Editor
         */
        holder: 'editorjs',

        onReady: () => {
            new DragDrop(editor);
        },

        /**
         * Tools list
         */
        tools: {
            raw: RawTool,
            /**
             * Each Tool is a Plugin. Pass them via 'class' option with necessary settings {@link docs/tools.md}
             */
            header: {
                class: Header,
                inlineToolbar: true,
                config: {
                    placeholder: 'Header'
                },
                shortcut: 'CMD+SHIFT+H'
            },

            underline: Underline,

            personality: {
                class: Personality,
                config: {
                    endpoint: '<?= url("upload-image"); ?>'
                }
            },

            /**
             * Or pass class directly without any configuration
             */
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '<?= url("upload-image"); ?>'
                    }
                }
            },

            list: {
                class: List,
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+L'
            },

            checklist: {
                class: Checklist,
                inlineToolbar: true,
            },

            quote: {
                class: Quote,
                inlineToolbar: true,
                config: {
                    quotePlaceholder: 'Enter a quote',
                    captionPlaceholder: 'Quote\'s author',
                },
                shortcut: 'CMD+SHIFT+O'
            },

            warning: Warning,

            marker: {
                class: Marker,
                shortcut: 'CMD+SHIFT+M'
            },

            code: {
                class: CodeTool,
                shortcut: 'CMD+SHIFT+C'
            },

            delimiter: Delimiter,

            embed: {
                class: Embed,
                inlineToolbar: true
            },

            inlineCode: {
                class: InlineCode,
                shortcut: 'CMD+SHIFT+C'
            },

            linkTool: {
                class: LinkTool,
                config: {
                    endpoint: '<?= url("url-info"); ?>',
                }
            },

            table: {
                class: Table,
                inlineToolbar: true,
                shortcut: 'CMD+ALT+T'
            },
        },

        /**
         * Initial Editor data
         */
        data: content
    });

    /**
     * Saving example
     */
    function setTextareaValue() {
        editor.save()
            .then((savedData) => {
                savedData.blocks.forEach((element) => {
                    switch (element.type) {
                        case 'raw':
                            element.data.html = btoa(element.data.html);
                            break;
                        case 'code':
                            element.data.code = btoa(element.data.code);
                            break;
                        case 'paragraph':
                        case 'header':
                            element.data.text = replaceContent(element.data.text);
                            break;
                        case 'list':
                            element.data.items.forEach((value, index) => {
                                element.data.items[index] = replaceContent(value);
                            });
                            break;
                        case 'checklist':
                            element.data.items.forEach((value, index) => {
                                element.data.items[index].text = replaceContent(value.text);
                            });
                            break;
                        case 'quote':
                            element.data.text = replaceContent(element.data.text);
                            element.data.caption = replaceContent(element.data.caption);
                            break;
                        case 'table':
                            element.data.content.forEach((row, indexRow) => {
                                row.forEach((column, indexColumn) => {
                                    element.data.content[indexRow][indexColumn] = replaceContent(column);
                                });
                            });
                            break;
                        case 'personality':
                            element.data.name = replaceContent(element.data.name);
                            element.data.description = replaceContent(element.data.description);
                            element.data.link = replaceContent(element.data.link);
                            break;
                    }
                });

                $('#content_field').val(JSON.stringify(savedData));

                load('panel/blog_codex/edit/<?= $this->blog->id; ?>', 'form:#form_box'); return false;
            })
            .catch((error) => {
                console.error('Saving error', error);
            });
    }

    function replaceContent(content)
    {
        return content.replace(/&nbsp;/g, " ").replace(/\n/g, " ").replace(/"/g, '\\"');
    }
</script>
