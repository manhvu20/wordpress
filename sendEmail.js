const nodemailer = require('nodemailer');

async function sendEmail() {
  // Thông tin email server
  let transporter = nodemailer.createTransport({
    host: 'smtp-relay.brevo.com',
    port: 587,
    secure: false, // false nếu sử dụng port 587
    auth: {
      user: 'h5studiogl@gmail.com',
      pass: 'fScdnZ4WmEDqjBA1',
    },
  });

  // Nội dung email
  let info = await transporter.sendMail({
    from: 'h5studiogl@gmail.com',
    to: 'manhvun20dccn001@gmail.com',
    subject: 'Thông báo số commit mới',
    text: 'Có commit mới trên repository của bạn.',
    html: '<p>Có commit mới trên repository của bạn.</p>',
  });

  console.log('Email sent: ' + info.messageId);
}

sendEmail().catch(console.error);
