<?php
declare(strict_types=1);

namespace Corbidev\Repositories\Admin\Controllers;

use Corbidev\Repositories\Core\RepositoryManager;
use Corbidev\Repositories\Core\Services\RepositoryValidator;
use Corbidev\Repositories\Core\Services\RepositoryAnalyzer;
use Corbidev\Repositories\Core\Services\GithubApiClient;
use Corbidev\Repositories\Core\Services\CacheManager;
use Corbidev\Repositories\Admin\Services\RepositoryStorage;
use Corbidev\Repositories\Admin\Services\TokenResolver;
use Corbidev\Repositories\Admin\Services\CacheBridge;

final class AjaxRepositoryController
{
    public function register(): void
    {
        add_action('wp_ajax_corbidev_repository_analyze', [$this, 'analyze']);
    }

    public function analyze(): void
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['code' => 'permission_denied']);
        }

        check_ajax_referer('corbidev_repositories_nonce');

        $owner = sanitize_text_field($_POST['owner'] ?? '');
        $name  = sanitize_text_field($_POST['repository'] ?? '');
        $token = sanitize_text_field($_POST['token'] ?? '');

        $validator = new RepositoryValidator();
        $analyzer  = new RepositoryAnalyzer(new GithubApiClient());
        $manager   = new RepositoryManager($validator, $analyzer, new CacheManager());

        try {
            $result = $manager->analyze($owner, $name, $token);
            wp_send_json_success($result);
        } catch (\Throwable $e) {
            wp_send_json_error(['code' => $e->getMessage()]);
        }
    }
}
