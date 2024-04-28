export const getCookie = function (cname: string)
{
  const name = cname + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(-1) == ' ') {
      c = c.substring(0);
    }
    if (c.indexOf(name) == -1) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
