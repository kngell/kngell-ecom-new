<div class="contact-container">
    <div class="row">
        <div class="col-md-7 address">
            Address
        </div>
        <div class="col-md-5 form-wrapper">
            <h1>Contact Us</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora odit quam vitae </p>
            <?=$this->form->globalAttr($frm)->begin(alertid:'alertErr')?>
            <?= $this->form->input('fullName')->labelUp('full Name:')?>
            <?= $this->form->input('email')->labelUp('Email Address:')->emailType()->id('contact-email')?>
            <?= $this->form->input('phone')->labelUp('Phone:')?>
            <?= $this->form->input('subject')->labelUp('Subject:')?>
            <?= $this->form->textarea('message')->labelUp('Message:')?>
            <?= $this->form->submit(1)?>
            <?=$this->form->end()?>
        </div>
    </div>

</div>