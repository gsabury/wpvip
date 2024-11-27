<div class="metabox_inside">
    <p>
        <label for="wpvip_plan">لطفا طرح مورد نظر را انتخاب کنید : </label>
        <select name="wpvip_plan" id="wpvip_plan">
            <?php if ($plans && count($plans) > 0): ?>
                <option value="0">در دسترس همه</option>
                <?php foreach ($plans as $plan) : ?>
                    <option value="<?php echo $plan->plan_ID; ?>" <?php selected($is_current_post_vip, $plan->plan_ID); ?>>
                        <?php echo $plan->title; ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </p>
</div>