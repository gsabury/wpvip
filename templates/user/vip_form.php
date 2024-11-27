<?php if (is_user_logged_in()): ?>
    <?php if (isset($options['general']['wpvip_is_active']) && intval($options['general']['wpvip_is_active'])): ?>
        <div class="wpvip_frm">

            <?php wpvip_flash_msg(); ?>

            <form action="" method="post">

                <div class="form-row">
                    <label for="wpvip_plan">طرح مورد نظر : </label>
                    <select name="plan" id="wpvip_plan" class="wpvip_form_control">
                        <option value="0">لطفا انتخاب کنید ...</option>
                        <?php if (count($plans) > 0): ?>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?php echo $plan->plan_ID; ?>"><?php echo $plan->title . ' ( ' . wpvip_format_money($plan->price) . ' ) '; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-row">
                    <span>موجودی شما : </span>
                    <span><?php echo wpvip_format_money($current_user_wallet); ?></span>
                </div>

                <div class="form-row">
                    <label for="wpvip_code">کد تخفیف : </label>
                    <input type="text" name="off_code" id="wpvip_code" class="wpvip_form_control">
                </div>
                <div class="form-row">
                    <lable for="">حساب کاربری</lable>
                    <input type="radio" name="payment" value="account" checked>
                    <lable for="">پرداخت آنلاین</lable>
                    <input type="radio" name="payment" value="online">
                </div>
                <div class="form-row">
                    <input type="submit" name="wpvip_frm_submit" value="خرید عضویت ویژه" class="wpvip_submit">
                </div>
            </form>
        </div>
    <?php endif; ?>
<?php else: ?>
    <h1 class="wpvip_not_login">برای مشاهده فرم طرح اشتراک لطف نموده ابتدا لاگین شوید
        <a href="<?php echo wp_login_url() ?>">ورود</a>
    </h1>
<?php endif; ?>