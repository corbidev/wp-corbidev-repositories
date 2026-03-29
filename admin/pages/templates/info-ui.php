<?php if (!defined('ABSPATH')) exit; ?>

<p>
    <?php echo esc_html__('Corbidev UI is a lightweight UI system designed for WordPress plugins and themes. It provides reusable components like Modal and Banner, with a clean separation between UI and business logic.', 'corbidevrepositories'); ?>
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
    <li><?php echo esc_html__('No inline JavaScript in PHP (no onclick, no script tags)', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Do not use alert() or confirm()', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use CorbidevUI for all UI interactions', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('One action = one mode (await OR data-ui)', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2>🧩 <?php echo esc_html__('Global Object', 'corbidevrepositories'); ?></h2>

<pre><code>window.CorbidevUI</code></pre>

<p><?php echo esc_html__('Available everywhere (admin + front).', 'corbidevrepositories'); ?></p>

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

<hr>

<h2>🧠 <?php echo esc_html__('Best Practices', 'corbidevrepositories'); ?></h2>

<ul>
    <li><?php echo esc_html__('Use async/await for business logic', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use data-ui for UI only', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Always separate UI from logic', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Use banner for feedback', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2>❌ <?php echo esc_html__('Anti-patterns', 'corbidevrepositories'); ?></h2>

<ul>
    <li><code>onclick="..."</code></li>
    <li><code>alert()</code> / <code>confirm()</code></li>
    <li><?php echo esc_html__('Mixing await and event for the same action', 'corbidevrepositories'); ?></li>
    <li><?php echo esc_html__('Business logic inside UI components', 'corbidevrepositories'); ?></li>
</ul>

<hr>

<h2>🧪 <?php echo esc_html__('Live Demo', 'corbidevrepositories'); ?></h2>

<button class="button button-primary"
    data-ui="modal"
    data-ui-title="Demo"
    data-ui-message="Hello dev 👋">
    <?php echo esc_html__('Open Modal', 'corbidevrepositories'); ?>
</button>

<button class="button"
    data-ui="confirm"
    data-ui-title="Confirm"
    data-ui-message="Are you sure?">
    <?php echo esc_html__('Open Confirm', 'corbidevrepositories'); ?>
</button>

<button class="button"
    data-ui="banner"
    data-ui-message="Banner demo"
    data-ui-type="success">
    <?php echo esc_html__('Show Banner', 'corbidevrepositories'); ?>
</button>