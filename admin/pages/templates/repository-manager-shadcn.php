<?php if (!defined('ABSPATH')) exit; ?>

<div class="cdr-page-shell">
    <div class="cdr-page-stack">
        <section class="cdr-page-header">
            <span class="cdr-page-eyebrow"><?php echo esc_html__('Repositories', 'corbidevrepositories'); ?></span>
            <h1 class="cdr-page-title"><?php echo esc_html__('GitHub repository manager', 'corbidevrepositories'); ?></h1>
            <p class="cdr-page-description">
                <?php echo esc_html__('Manage the source repositories used by the plugin to discover Corbidev packages.', 'corbidevrepositories'); ?>
            </p>
        </section>

        <section class="cdr-card">
            <div class="cdr-card-header">
                <h2 class="cdr-card-title"><?php echo esc_html__('Add a repository', 'corbidevrepositories'); ?></h2>
                <p class="cdr-card-description">
                    <?php echo esc_html__('Add a repository owner and an optional token for authenticated GitHub access.', 'corbidevrepositories'); ?>
                </p>
            </div>
            <div class="cdr-card-body">
                <form class="cdr-page-stack" id="cdr-repo-form" method="post">
                    <div class="cdr-grid">
                        <label class="cdr-field">
                            <span class="cdr-label"><?php echo esc_html__('Repository owner', 'corbidevrepositories'); ?></span>
                            <input
                                class="cdr-input"
                                type="text"
                                name="name"
                                placeholder="<?php echo esc_attr__('e.g. corbidev', 'corbidevrepositories'); ?>"
                                required
                            />
                        </label>
                        <label class="cdr-field">
                            <span class="cdr-label"><?php echo esc_html__('Token', 'corbidevrepositories'); ?></span>
                            <input
                                class="cdr-input"
                                type="text"
                                name="token"
                                placeholder="<?php echo esc_attr__('Optional access token', 'corbidevrepositories'); ?>"
                            />
                        </label>
                    </div>

                    <div class="cdr-actions">
                        <button class="cdr-btn cdr-btn-primary" type="submit">
                            <?php echo esc_html__('Add repository', 'corbidevrepositories'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="cdr-card">
            <div class="cdr-card-header">
                <h2 class="cdr-card-title"><?php echo esc_html__('Configured repositories', 'corbidevrepositories'); ?></h2>
                <p class="cdr-card-description">
                    <?php echo esc_html__('The default Corbidev repository remains available as a protected fallback source.', 'corbidevrepositories'); ?>
                </p>
            </div>
            <div class="cdr-card-body">
                <div class="cdr-table-wrap">
                    <table class="cdr-table">
                        <thead>
                            <tr>
                                <th><?php echo esc_html__('Name', 'corbidevrepositories'); ?></th>
                                <th><?php echo esc_html__('Access', 'corbidevrepositories'); ?></th>
                                <th><?php echo esc_html__('Actions', 'corbidevrepositories'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($repos as $repo): ?>
                                <?php $is_default = ($repo['name'] ?? '') === 'corbidev'; ?>
                                <tr>
                                    <td data-label="<?php echo esc_attr__('Name', 'corbidevrepositories'); ?>">
                                        <div class="cdr-repo-meta">
                                            <span class="cdr-repo-name"><?php echo esc_html($repo['name']); ?></span>
                                            <?php if ($is_default): ?>
                                                <span class="cdr-inline-meta"><?php echo esc_html__('Default source', 'corbidevrepositories'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td data-label="<?php echo esc_attr__('Access', 'corbidevrepositories'); ?>">
                                        <form class="cdr-repo-access-form" data-repo-name="<?php echo esc_attr($repo['name']); ?>">
                                            <div class="cdr-repo-access-head">
                                                <span class="cdr-inline-meta">
                                                    <?php echo !empty($repo['token']) ? esc_html__('Token configured', 'corbidevrepositories') : esc_html__('Public access', 'corbidevrepositories'); ?>
                                                </span>
                                                <?php if ($is_default): ?>
                                                    <span class="cdr-repo-note"><?php echo esc_html__('The default repository name is locked.', 'corbidevrepositories'); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="cdr-repo-access-controls">
                                                <input
                                                    class="cdr-input cdr-input-compact"
                                                    type="password"
                                                    name="token"
                                                    value="<?php echo esc_attr($repo['token'] ?? ''); ?>"
                                                    placeholder="<?php echo esc_attr__('Personal access token', 'corbidevrepositories'); ?>"
                                                    autocomplete="off"
                                                />
                                                <div class="cdr-actions">
                                                    <button
                                                        class="cdr-btn cdr-btn-secondary"
                                                        data-action="repo-save-token"
                                                        data-name="<?php echo esc_attr($repo['name']); ?>"
                                                        type="submit"
                                                    >
                                                        <?php echo esc_html__('Save access', 'corbidevrepositories'); ?>
                                                    </button>
                                                    <button
                                                        class="cdr-btn cdr-btn-ghost"
                                                        data-action="repo-clear-token"
                                                        data-name="<?php echo esc_attr($repo['name']); ?>"
                                                        type="button"
                                                    >
                                                        <?php echo esc_html__('Clear token', 'corbidevrepositories'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td data-label="<?php echo esc_attr__('Actions', 'corbidevrepositories'); ?>">
                                        <div class="cdr-actions">
                                            <?php if ($is_default): ?>
                                                <span class="cdr-repo-note"><?php echo esc_html__('Protected repository', 'corbidevrepositories'); ?></span>
                                            <?php else: ?>
                                                <button
                                                    class="cdr-btn cdr-btn-danger"
                                                    data-action="repo-delete"
                                                    data-name="<?php echo esc_attr($repo['name']); ?>"
                                                    type="button"
                                                >
                                                    <?php echo esc_html__('Delete', 'corbidevrepositories'); ?>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
