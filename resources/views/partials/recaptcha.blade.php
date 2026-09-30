<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    window.getRecaptchaToken = function (action) {
        return new Promise(function (resolve, reject) {
            if (typeof grecaptcha === 'undefined') {
                reject('reCAPTCHA not loaded');
                return;
            }
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: action })
                    .then(resolve)
                    .catch(reject);
            });
        });
    };
</script>
