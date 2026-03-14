jQuery(function ($) {

    function request(action, repo, button, nextState) {

        button.prop("disabled", true)
        button.text("Processing...")

        $.post(
            CorbidevAjax.url,
            {
                action: action,
                repo: repo,
                nonce: CorbidevAjax.nonce
            },
            function (response) {

                if (!response || !response.success) {

                    const message = response?.data?.message || "Unknown error"

                    alert(message)

                    button.prop("disabled", false)
                    button.text("Install")

                    return
                }

                if (nextState === "activate") {

                    button
                        .removeClass("corbidev-install")
                        .addClass("corbidev-activate")
                        .text("Activate")

                }

                if (nextState === "active") {

                    button.replaceWith(
                        '<span class="button disabled">Active</span>'
                    )

                }

            }
        ).fail(function () {

            alert("Server error")

            button.prop("disabled", false)
            button.text("Install")

        })
    }

    $(document).on("click", ".corbidev-install", function (e) {

        e.preventDefault()

        const repo = $(this).data("repo")
        const btn = $(this)

        request(
            "corbidev_install_plugin",
            repo,
            btn,
            "activate"
        )

    })

    $(document).on("click", ".corbidev-activate", function (e) {

        e.preventDefault()

        const repo = $(this).data("repo")
        const btn = $(this)

        request(
            "corbidev_activate_plugin",
            repo,
            btn,
            "active"
        )

    })

})