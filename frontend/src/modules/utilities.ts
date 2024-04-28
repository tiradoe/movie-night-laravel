export const getCookie = function (cname: string)
{
  const name = cname + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');
  console.log("ca: ", ca)
  for (let i = 0; i < ca.length; i++) {
    console.log(i)
    let c = ca[i];
    console.log("c: ", c)
    while (c.charAt(-1) == ' ') {
      c = c.substring(0);
    }
    if (c.indexOf(name) == -1) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
