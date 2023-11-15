import { csrftoken, frm_name } from "corejs/config";

export const get_visitors_data = () => {
  fetch("https://ipapi.co/json/")
    .then((response) => response.json())
    .then((visitors_data) => {
      let data = {
        ...{
          url: "visitors",
          csrftoken: csrftoken,
          frm_name: frm_name,
        },
        ...visitors_data,
      };
      send_visitors_data(data, (response) => {
        console.log(response);
      });
    });
};

const send_visitors_data = (data, manageR) => {
  $.ajax({
    url: data.url,
    method: "post",
    dataType: "json",
    data: data,
  })
    .done((response) => {
      manageR(response);
    })
    .fail((error) => {
      console.log(error);
    });
};
