<?php if (!defined('ABSPATH')) exit; ?>

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
