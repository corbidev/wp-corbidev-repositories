<?php if (!defined('ABSPATH')) exit; ?>

<p>
    <?php echo esc_html__('This screen documents the Corbidev admin UI stack: shadcn-inspired styling for presentation, and CorbidevUI for interactive behaviors such as modals, banners, loading states, and AJAX helpers.', 'corbidevrepositories'); ?>
</p>

<hr>

<h2><?php echo esc_html__('What "shadcn" means here', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('In this plugin, "shadcn" refers to the visual and component conventions used to build the admin interface: clean card layouts, structured spacing, consistent buttons, tabs, badges, and utility-first styling powered by Tailwind.', 'corbidevrepositories'); ?>
</p>

<p>
    <?php echo esc_html__('It is important to separate two layers:', 'corbidevrepositories'); ?>
</p>

<ul>
    <li><?php echo esc_html__('shadcn-style UI = the visual system and component appearance', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('CorbidevUI = the runtime interaction bridge for modal, banner, toast, loading, and AJAX helpers', 'corbidevrepositories'); ?></li>
</ul>

<pre><code>// Visual layer
cards, tabs, buttons, badges, layouts

// Interaction layer
window.CorbidevUI.modal.open(...)
window.CorbidevUI.banner.show(...)
window.CorbidevUI.ajax.request(...)</code></pre>

<hr>

<h2><?php echo esc_html__('How it is loaded', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('The Corbidev Repositories plugin automatically loads the UI bridge and admin assets only on admin pages whose slug starts with "corbidev". That means CorbidevUI is available on the plugin admin pages, but not automatically on unrelated plugin pages or frontend templates.', 'corbidevrepositories'); ?>
</p>

<ul>
    <li><?php echo esc_html__('Corbidev admin pages: bridge available automatically', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Your own plugin admin page: you must enqueue the same assets or expose your own bridge entry', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Theme frontend templates: the admin bridge is not automatically mounted there', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2><?php echo esc_html__('Recommended architecture', 'corbidevrepositories'); ?></h2>

<ul>
    <li><?php echo esc_html__('Use PHP templates for structure and escaped data output', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use shadcn-style classes and shared admin CSS for layout and styling', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use CorbidevUI for interactions instead of inline JavaScript, alert(), or confirm()', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Keep business logic in JavaScript modules or PHP handlers, not inside markup snippets', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2><?php echo esc_html__('Integration inside a plugin admin page', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('If your plugin renders content inside the Corbidev admin experience, you can directly use the shared visual language and CorbidevUI APIs.', 'corbidevrepositories'); ?>
</p>

<h3><?php echo esc_html__('Example: shadcn-style card in PHP', 'corbidevrepositories'); ?></h3>

<pre><code>&lt;section class="cdr-card"&gt;
    &lt;div class="cdr-card-header"&gt;
        &lt;h2 class="cdr-card-title"&gt;Deployment&lt;/h2&gt;
        &lt;p class="cdr-card-description"&gt;
            Trigger and monitor deployment actions.
        &lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="cdr-card-body"&gt;
        &lt;div class="cdr-actions"&gt;
            &lt;button class="cdr-btn cdr-btn-primary" type="button"&gt;
                Deploy
            &lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/section&gt;</code></pre>

<h3><?php echo esc_html__('Example: modal workflow in JavaScript', 'corbidevrepositories'); ?></h3>

<pre><code>document.addEventListener('click', async (event) =&gt; {
    const button = event.target.closest('[data-action="deploy"]');
    if (!button || !window.CorbidevUI) {
        return;
    }

    const confirmed = await window.CorbidevUI.modal.confirm({
        title: 'Deploy release',
        message: 'Do you want to deploy the current release?',
        type: 'danger',
    });

    if (!confirmed) {
        return;
    }

    window.CorbidevUI.loading.set(button, true);

    try {
        await window.CorbidevUI.ajax.request('my_plugin_deploy', {
            environment: 'production',
        });

        window.CorbidevUI.banner.show({
            message: 'Deployment started successfully.',
            type: 'success',
        });
    } catch (error) {
        window.CorbidevUI.error.handle(error);
    } finally {
        window.CorbidevUI.loading.set(button, false);
    }
});</code></pre>

<hr>

<h2><?php echo esc_html__('Integration inside another plugin', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('If your plugin uses its own admin page, do not assume CorbidevUI is globally available. You have two main options:', 'corbidevrepositories'); ?>
</p>

<ul>
    <li><?php echo esc_html__('Reuse the Corbidev assets by enqueueing the relevant compiled entry on your page', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Replicate the same architecture in your own bundle with your own bridge entry', 'corbidevrepositories'); ?></li>
</ul>

<h3><?php echo esc_html__('Safe guard before using the bridge', 'corbidevrepositories'); ?></h3>

<pre><code>document.addEventListener('DOMContentLoaded', () =&gt; {
    if (!window.CorbidevUI) {
        console.warn('CorbidevUI is not available on this screen.');
        return;
    }

    window.CorbidevUI.banner.show({
        message: 'UI bridge ready.',
        type: 'info',
    });
});</code></pre>

<p>
    <?php echo esc_html__('This guard is especially important when code can run on screens that are not owned by Corbidev.', 'corbidevrepositories'); ?>
</p>

<hr>

<h2><?php echo esc_html__('Integration inside a theme', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('For theme templates or frontend pages, do not rely on the admin bridge by default. The visual language can still inspire your components, but frontend integration should be explicit and isolated.', 'corbidevrepositories'); ?>
</p>

<ul>
    <li><?php echo esc_html__('Do not assume admin assets are loaded on the frontend', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Do not couple a public theme template to a plugin admin-only runtime unless you intentionally enqueue it', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Prefer creating a dedicated frontend entry if your theme needs banners, dialogs, or custom interactions', 'corbidevrepositories'); ?></li>
</ul>

<h3><?php echo esc_html__('Example: frontend-safe markup pattern', 'corbidevrepositories'); ?></h3>

<pre><code>&lt;section class="my-theme-panel"&gt;
    &lt;h2&gt;Newsletter preferences&lt;/h2&gt;
    &lt;p&gt;Update the communication options for the current customer.&lt;/p&gt;
    &lt;button class="my-theme-button" type="button"&gt;
        Save preferences
    &lt;/button&gt;
&lt;/section&gt;</code></pre>

<p>
    <?php echo esc_html__('If you want the exact Corbidev interaction model on the frontend, treat it as an intentional product decision and enqueue a dedicated script entry rather than depending on an admin page side effect.', 'corbidevrepositories'); ?>
</p>

<hr>

<h2><?php echo esc_html__('Personalizing the design system', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('The recommended customization path is to keep the component structure stable and override the design tokens or utility composition around it.', 'corbidevrepositories'); ?>
</p>

<h3><?php echo esc_html__('What is safe to customize', 'corbidevrepositories'); ?></h3>

<ul>
    <li><?php echo esc_html__('Spacing, border radius, shadows, and typography scale', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Color palette and contrast levels', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Component variants such as primary, danger, outline, and ghost buttons', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Card layouts and empty states', 'corbidevrepositories'); ?></li>
</ul>

<h3><?php echo esc_html__('What should stay stable', 'corbidevrepositories'); ?></h3>

<ul>
    <li><?php echo esc_html__('The CorbidevUI API surface', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('The semantic meaning of statuses and actions', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Accessibility behaviors such as closable dialogs, focus flow, and readable contrast', 'corbidevrepositories'); ?></li>
</ul>

<h3><?php echo esc_html__('Example: custom button family', 'corbidevrepositories'); ?></h3>

<pre><code>@layer components {
  .my-admin-btn {
    @apply inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold;
  }

  .my-admin-btn-primary {
    @apply my-admin-btn bg-slate-950 text-white hover:bg-slate-800;
  }

  .my-admin-btn-danger {
    @apply my-admin-btn bg-red-600 text-white hover:bg-red-700;
  }
}</code></pre>

<p>
    <?php echo esc_html__('This lets you create a branded variant while preserving the overall component architecture.', 'corbidevrepositories'); ?>
</p>

<hr>

<h2><?php echo esc_html__('Declarative mode with data-ui', 'corbidevrepositories'); ?></h2>

<p>
    <?php echo esc_html__('For simple interactions, you can use declarative HTML instead of writing custom JavaScript.', 'corbidevrepositories'); ?>
</p>

<pre><code>&lt;button
    class="cdr-btn cdr-btn-outline"
    data-ui="modal"
    data-ui-title="About this package"
    data-ui-message="This package installs shared tools for editors."&gt;
    Open modal
&lt;/button&gt;

&lt;button
    class="cdr-btn cdr-btn-secondary"
    data-ui="banner"
    data-ui-message="Configuration saved."
    data-ui-type="success"
    data-ui-delay="3"&gt;
    Show banner
&lt;/button&gt;</code></pre>

<p>
    <?php echo esc_html__('Use this mode for lightweight interactions. For anything involving business rules, asynchronous requests, or branching flows, prefer the JavaScript API.', 'corbidevrepositories'); ?>
</p>

<hr>

<h2><?php echo esc_html__('Advanced modal example with custom buttons', 'corbidevrepositories'); ?></h2>

<pre><code>const result = await window.CorbidevUI.modal.open({
    title: 'Choose an action',
    message: 'Select how you want to handle this repository.',
    buttons: [
        {
            label: 'Cancel',
            type: 'secondary',
            value: 'cancel',
        },
        {
            label: 'Retry',
            type: 'outline',
            value: 'retry',
        },
        {
            label: 'Delete',
            type: 'danger',
            value: 'delete',
        },
    ],
});

if (result === 'retry') {
    // retry action
}

if (result === 'delete') {
    // delete action
}</code></pre>

<hr>

<h2><?php echo esc_html__('Best practices', 'corbidevrepositories'); ?></h2>

<ul>
    <li><?php echo esc_html__('Prefer consistent shared classes over one-off inline styles in templates', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use CorbidevUI.error.handle(error) for unexpected runtime errors', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Guard for window.CorbidevUI before using it outside Corbidev-owned admin pages', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Keep templates declarative and move imperative flows into JavaScript modules', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Treat frontend usage as a separate integration target, not as an automatic extension of the admin runtime', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2><?php echo esc_html__('Live demo', 'corbidevrepositories'); ?></h2>

<button
    class="button button-primary"
    data-ui="modal"
    data-ui-title="Corbidev UI"
    data-ui-message="This modal is rendered through the shared CorbidevUI bridge."
>
    <?php echo esc_html__('Open modal', 'corbidevrepositories'); ?>
</button>

<button
    class="button"
    data-ui="confirm"
    data-ui-title="Confirm action"
    data-ui-message="Do you want to continue with this example?"
>
    <?php echo esc_html__('Open confirm', 'corbidevrepositories'); ?>
</button>

<button
    class="button"
    data-ui="banner"
    data-ui-message="Banner rendered through the UI bridge."
    data-ui-type="success"
>
    <?php echo esc_html__('Show banner', 'corbidevrepositories'); ?>
</button>

<?php return; ?>

<p>
    <?php echo esc_html__('Corbidev UI is the plugin UI bridge used on Corbidev admin screens. It provides reusable interactions like Modal and Banner, with a clean separation between UI and business logic.', 'corbidevrepositories'); ?>
</p>

<hr>

<h2>🎯 <?php echo esc_html__('Core Principles', 'corbidevrepositories'); ?></h2>

<ul>
    <li><?php echo esc_html__('UI is declarative (HTML → data-*)', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Logic is handled in JavaScript (async / await)', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('No business logic inside UI components', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Reusable across plugins and themes', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2>🧠 <?php echo esc_html__('Core Rules (Important)', 'corbidevrepositories'); ?></h2>

<ul>
    <li><?php echo esc_html__('No inline JavaScript in PHP (no onclick, no script tags)', 'corbidevrepositories'); ?>
    </li>
    <li><?php echo esc_html__('Do not use alert() or confirm()', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use CorbidevUI for all UI interactions', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('One action = one mode (await OR data-ui)', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2>🧩 <?php echo esc_html__('Global Object', 'corbidevrepositories'); ?></h2>

<pre><code>window.CorbidevUI</code></pre>

<p><?php echo esc_html__('Available on Corbidev admin screens where the plugin assets are loaded.', 'corbidevrepositories'); ?></p>

<hr>

<h2>🚀 <?php echo esc_html__('Usage Modes', 'corbidevrepositories'); ?></h2>

<h3>1. <?php echo esc_html__('JavaScript Mode (Recommended)', 'corbidevrepositories'); ?></h3>

<pre><code>const confirmed = await CorbidevUI.modal.confirm({
    title: "Delete",
    message: "Are you sure?"
});

if (!confirmed) return;

deleteItem();</code></pre>

<p><?php echo esc_html__('Best for business logic and complex workflows.', 'corbidevrepositories'); ?></p>

<h3>2. <?php echo esc_html__('HTML Mode (data-ui)', 'corbidevrepositories'); ?></h3>

<pre><code>&lt;button
    data-ui="modal"
    data-ui-title="Hello"
    data-ui-message="This is a modal"&gt;
&lt;/button&gt;</code></pre>

<p><?php echo esc_html__('No JavaScript required. Ideal for themes and simple UI.', 'corbidevrepositories'); ?></p>

<h3>3. <?php echo esc_html__('Hybrid Mode (Event-based)', 'corbidevrepositories'); ?></h3>

<pre><code>&lt;button
    data-ui="confirm"
    data-ui-title="Delete"
    data-ui-message="Confirm?"
    data-action="delete"
    data-id="42"&gt;
&lt;/button&gt;</code></pre>

<pre><code>document.addEventListener('corbidev:confirm', (e) =&gt; {
    const el = e.target.closest('[data-action="delete"]');
    if (!el) return;

    deleteItem(el.dataset.id);
});</code></pre>

<hr>

<h2>🔧 <?php echo esc_html__('data-ui Attributes', 'corbidevrepositories'); ?></h2>

<ul>
    <li><code>data-ui</code> → modal / confirm / banner</li>
    <li><code>data-ui-title</code> → <?php echo esc_html__('Modal title', 'corbidevrepositories'); ?></li>
    <li><code>data-ui-message</code> → <?php echo esc_html__('Content', 'corbidevrepositories'); ?></li>
    <li><code>data-ui-type</code> → success / danger / info</li>
    <li><code>data-ui-delay</code> → <?php echo esc_html__('Banner delay (seconds)', 'corbidevrepositories'); ?></li>
    <li><code>data-ui-position</code> → top / bottom</li>
    <li><code>data-ui-buttons</code> → JSON</li>
</ul>

<hr>

<h2>🧩 Modal API</h2>

<pre><code>await CorbidevUI.modal.open({
    title: "Hello",
    message: "World"
});</code></pre>

<pre><code>const result = await CorbidevUI.modal.confirm({...});</code></pre>

<p><?php echo esc_html__('Returns true / false.', 'corbidevrepositories'); ?></p>

<hr>

<h2>🔔 Banner API</h2>

<pre><code>CorbidevUI.banner.show({
    message: "Saved",
    type: "success"
});</code></pre>

<hr>

<h2>⚡ <?php echo esc_html__('Events', 'corbidevrepositories'); ?></h2>

<pre><code>CorbidevUI.on('modal.open', cb);
CorbidevUI.on('banner.open', cb);</code></pre>

<pre><code>document.addEventListener('corbidev:confirm', (e) =&gt; {
    console.log(e.detail.confirmed);
});</code></pre>

<h2>📦 <?php echo esc_html__('Integration in Your Plugin/Theme', 'corbidevrepositories'); ?></h2>

<p><?php echo esc_html__('The Corbidev UI bridge is automatically loaded by the Corbidev Repositories plugin on its admin screens. You don\'t need to do anything special - just use CorbidevUI in your code!', 'corbidevrepositories'); ?>
</p>

<p><strong><?php echo esc_html__('Prerequisites:', 'corbidevrepositories'); ?></strong>
    <?php echo esc_html__('Corbidev Repositories plugin must be active.', 'corbidevrepositories'); ?></p>

<h3><?php echo esc_html__('Use CorbidevUI in Your Code', 'corbidevrepositories'); ?></h3>

<pre><code>// In your main.js
document.addEventListener('DOMContentLoaded', () => {
    if (!window.CorbidevUI) {
        console.error('CorbidevUI not loaded');
        return;
    }

    // Now you can use CorbidevUI
    await CorbidevUI.modal.open({
        title: 'My Modal',
        message: 'Hello from my plugin!'
    });
});</code></pre>

<h3><?php echo esc_html__('HTML Markup', 'corbidevrepositories'); ?></h3>

<pre><code>&lt;!-- Use data-ui for declarative UI --&gt;
&lt;button
    class="button"
    data-ui="banner"
    data-ui-message="Operation completed"
    data-ui-type="success"&gt;
    Show Banner
&lt;/button&gt;</code></pre>

<hr>

<h2>📋 <?php echo esc_html__('Complete Examples', 'corbidevrepositories'); ?></h2>

<h3><?php echo esc_html__('Modal Examples', 'corbidevrepositories'); ?></h3>

<p><strong><?php echo esc_html__('Simple Modal', 'corbidevrepositories'); ?></strong></p>

<pre><code>await CorbidevUI.modal.open({
    title: "Welcome",
    message: "Hello there!"
});</code></pre>

<p><strong><?php echo esc_html__('Confirm Modal (returns boolean)', 'corbidevrepositories'); ?></strong></p>

<pre><code>const confirmed = await CorbidevUI.modal.confirm({
    title: "Delete Item",
    message: "This action cannot be undone.",
    type: "danger"
});

if (confirmed) {
    // Delete item
}</code></pre>

<p><strong><?php echo esc_html__('Modal with Custom Buttons', 'corbidevrepositories'); ?></strong></p>

<pre><code>const result = await CorbidevUI.modal.open({
    title: "Choose Action",
    message: "What would you like to do?",
    buttons: [
        {
            label: "Cancel",
            type: "secondary",
            value: null
        },
        {
            label: "Save",
            type: "primary",
            value: "save"
        },
        {
            label: "Delete",
            type: "danger",
            value: "delete"
        }
    ]
});

console.log(result); // "save" | "delete" | null</code></pre>

<p><strong><?php echo esc_html__('HTML Mode Modal', 'corbidevrepositories'); ?></strong></p>

<pre><code>&lt;button
    class="button"
    data-ui="modal"
    data-ui-title="Settings"
    data-ui-message="Configure your options"&gt;
    Open Settings
&lt;/button&gt;</code></pre>

<hr>

<h3><?php echo esc_html__('Banner Examples', 'corbidevrepositories'); ?></h3>

<p><strong><?php echo esc_html__('Simple Banner', 'corbidevrepositories'); ?></strong></p>

<pre><code>CorbidevUI.banner.show({
    message: "Operation completed"
});</code></pre>

<p><strong><?php echo esc_html__('Success Banner', 'corbidevrepositories'); ?></strong></p>

<pre><code>CorbidevUI.banner.show({
    message: "Item saved successfully!",
    type: "success",
    delay: 3  // Disappears after 3 seconds
});</code></pre>

<p><strong><?php echo esc_html__('Error Banner', 'corbidevrepositories'); ?></strong></p>

<pre><code>CorbidevUI.banner.show({
    message: "Failed to save changes",
    type: "danger",
    delay: 5,
    closable: true
});</code></pre>

<p><strong><?php echo esc_html__('Info Banner at Bottom', 'corbidevrepositories'); ?></strong></p>

<pre><code>CorbidevUI.banner.show({
    message: "New update available",
    type: "info",
    position: "bottom",
    delay: 0,  // Stays forever
    closable: true
});</code></pre>

<p><strong><?php echo esc_html__('Warning Banner', 'corbidevrepositories'); ?></strong></p>

<pre><code>CorbidevUI.banner.show({
    message: "This will affect all users",
    type: "warning",
    closable: true,
    autoClose: false  // Manual close only
});</code></pre>

<p><strong><?php echo esc_html__('HTML Mode Banner', 'corbidevrepositories'); ?></strong></p>

<pre><code>&lt;button
    class="button"
    data-ui="banner"
    data-ui-message="Changes saved"
    data-ui-type="success"
    data-ui-delay="3"&gt;
    Show Success
&lt;/button&gt;</code></pre>

<p><strong><?php echo esc_html__('Banner Types', 'corbidevrepositories'); ?></strong></p>

<ul>
    <li><code>type: 'info'</code> - <?php echo esc_html__('Blue', 'corbidevrepositories'); ?></li>
    <li><code>type: 'success'</code> - <?php echo esc_html__('Green', 'corbidevrepositories'); ?></li>
    <li><code>type: 'warning'</code> - <?php echo esc_html__('Amber', 'corbidevrepositories'); ?></li>
    <li><code>type: 'danger'</code> - <?php echo esc_html__('Red', 'corbidevrepositories'); ?></li>
    <li><code>type: 'neutral'</code> - <?php echo esc_html__('Gray', 'corbidevrepositories'); ?></li>
</ul>

<p><strong><?php echo esc_html__('Banner Options', 'corbidevrepositories'); ?></strong></p>

<ul>
    <li><code>message</code> - <?php echo esc_html__('Text to display (required)', 'corbidevrepositories'); ?></li>
    <li><code>type</code> -
        <?php echo esc_html__('Style: info, success, warning, danger, neutral', 'corbidevrepositories'); ?></li>
    <li><code>delay</code> - <?php echo esc_html__('Auto-close after N seconds (0 = never)', 'corbidevrepositories'); ?>
    </li>
    <li><code>position</code> - <?php echo esc_html__('top (default) or bottom', 'corbidevrepositories'); ?></li>
    <li><code>closable</code> - <?php echo esc_html__('Show close button (true)', 'corbidevrepositories'); ?></li>
    <li><code>autoClose</code> - <?php echo esc_html__('Auto-hide on timer (true)', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2>🧠 <?php echo esc_html__('Best Practices', 'corbidevrepositories'); ?></h2>

<ul>
    <li><code>onclick="..."</code></li>
    <li><code>alert()</code> / <code>confirm()</code></li>
    <li><?php echo esc_html__('Mixing await and event for the same action', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Business logic inside UI components', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2>🧪 <?php echo esc_html__('Live Demo', 'corbidevrepositories'); ?></h2>

<button class="button button-primary" data-ui="modal" data-ui-title="Demo" data-ui-message="Hello dev 👋">
    <?php echo esc_html__('Open Modal', 'corbidevrepositories'); ?>
</button>

<button class="button" data-ui="confirm" data-ui-title="Confirm" data-ui-message="Are you sure?">
    <?php echo esc_html__('Open Confirm', 'corbidevrepositories'); ?>
</button>

<button class="button" data-ui="banner" data-ui-message="Banner demo" data-ui-type="success">
    <?php echo esc_html__('Show Banner', 'corbidevrepositories'); ?>
</button>
