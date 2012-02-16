<!doctype html>
<?php

session_start();

if (empty($_GET['me'])) $_GET['me'] = 1;
if (empty($_GET['them'])) $_GET['them'] = 2;

if (empty($_SESSION['popupchat_csrf'])) {
    $_SESSION['popupchat_csrf'] = md5(time());
    $_SESSION['popupchat_me'] = $_GET['me'];
}

?>
<html>
<head>
<meta charset="utf-8">
<title>A jQuery popup chat</title>
<link charset="utf-8" rel="stylesheet" href="popupchat.css">
<script src="jquery-1.7.1.min.js"></script>
<script src="popupchat.js"></script>
</head>
<body>

<h2>CHAT BETWEEN YOU (USER ID <?= $_GET['me'] ?>) AND THEM (USER ID <?= $_GET['them'] ?>)</h2>

<p><a href="chat.php?me=<?= $_GET['them'] ?>&them=<?= $_GET['me'] ?>">Click to open the chat partner's chat window.</a></p>
<hr>
<p>This is a jQuery plugin popup chat.</p>
<p>Your CSRF token is "<?= $_SESSION['popupchat_csrf'] ?>".</p>
<hr>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sollicitudin mauris quis diam semper eget interdum purus sodales. In tempor purus id purus aliquet eleifend venenatis orci cursus. Mauris tempus turpis et elit porta ultrices. Integer ante augue, ullamcorper at blandit vel, dignissim a diam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed fringilla justo eget elit porttitor viverra. Aenean vel dolor ac nulla rutrum volutpat. Aenean vitae dolor justo, sit amet pharetra eros. In id sapien sem. Mauris sollicitudin orci in nibh condimentum ac sagittis massa commodo.</p>

<p>Ut sit amet purus et risus consectetur congue. Nunc convallis venenatis felis in condimentum. Nulla sed ante massa. Vestibulum ipsum orci, elementum ac egestas non, convallis quis tortor. Cras ac augue nibh. Suspendisse potenti. Curabitur egestas hendrerit velit, non venenatis mauris malesuada sed. Cras congue dolor congue felis vehicula dapibus. Nullam nibh nulla, luctus eu interdum dapibus, lobortis ut nibh. Nullam sagittis, erat ut vulputate interdum, diam velit feugiat lorem, sed sagittis dui enim in nulla. Aliquam a tortor et urna tincidunt imperdiet in et erat. Maecenas tempor, libero vel gravida blandit, erat tellus pulvinar quam, a varius sapien lorem eu nisi.</p>

<p>Etiam nisl odio, accumsan et dictum vehicula, volutpat eget lacus. Aenean ullamcorper venenatis est eget volutpat. Morbi vel metus non massa pretium dictum sit amet et ipsum. Nunc dignissim mi non nisi rhoncus tincidunt. Nam congue suscipit orci eget sodales. Proin imperdiet, nulla nec tristique cursus, massa nisi hendrerit diam, ut eleifend eros arcu at magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi suscipit dui ac tellus molestie vel tempus metus molestie. Proin dapibus lectus id mi dapibus in ultrices lacus porttitor. Curabitur viverra egestas elit, vel cursus orci semper vitae. Nam consequat malesuada lectus, nec congue neque rutrum ac. Nulla rutrum eleifend eleifend. Morbi commodo bibendum tellus, at mattis augue sodales ac.</p>

<p>Pellentesque vel tortor vitae tellus ultrices accumsan ut eu diam. Phasellus porta urna ac eros dapibus et porttitor risus pellentesque. Nullam fermentum volutpat tristique. Cras rutrum lacus vel magna pellentesque porttitor. Vivamus dignissim condimentum tellus, a sollicitudin magna aliquet sed. In hac habitasse platea dictumst. Ut blandit mattis mi id tristique. Quisque at ante ac enim dapibus vulputate vitae vitae est. Cras condimentum nulla sed risus tempor ut pretium sem lacinia. Nunc sit amet velit non odio adipiscing tincidunt ac vitae ante. Quisque nisl orci, commodo vitae venenatis nec, ornare scelerisque tortor. Nulla sagittis sagittis purus, sed rhoncus massa tempus vel. Duis arcu ligula, bibendum vitae consectetur vehicula, consequat vitae lectus.</p>

<p>Suspendisse et cursus neque. Pellentesque interdum, eros sed molestie consectetur, massa nibh vestibulum ante, et pellentesque tellus mi eget nisl. Morbi ultrices luctus pharetra. Maecenas ut quam tortor. Mauris ut tellus dui. Sed at nulla turpis, pretium vestibulum enim. Phasellus ornare, odio ut posuere mollis, felis diam molestie neque, non aliquam mauris leo at massa. Proin arcu nisi, condimentum non vulputate vitae, venenatis et metus. Nullam congue dui at nibh laoreet nec porta orci fermentum.</p>

<p>Ut sit amet purus et risus consectetur congue. Nunc convallis venenatis felis in condimentum. Nulla sed ante massa. Vestibulum ipsum orci, elementum ac egestas non, convallis quis tortor. Cras ac augue nibh. Suspendisse potenti. Curabitur egestas hendrerit velit, non venenatis mauris malesuada sed. Cras congue dolor congue felis vehicula dapibus. Nullam nibh nulla, luctus eu interdum dapibus, lobortis ut nibh. Nullam sagittis, erat ut vulputate interdum, diam velit feugiat lorem, sed sagittis dui enim in nulla. Aliquam a tortor et urna tincidunt imperdiet in et erat. Maecenas tempor, libero vel gravida blandit, erat tellus pulvinar quam, a varius sapien lorem eu nisi.</p>

<p>Etiam nisl odio, accumsan et dictum vehicula, volutpat eget lacus. Aenean ullamcorper venenatis est eget volutpat. Morbi vel metus non massa pretium dictum sit amet et ipsum. Nunc dignissim mi non nisi rhoncus tincidunt. Nam congue suscipit orci eget sodales. Proin imperdiet, nulla nec tristique cursus, massa nisi hendrerit diam, ut eleifend eros arcu at magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi suscipit dui ac tellus molestie vel tempus metus molestie. Proin dapibus lectus id mi dapibus in ultrices lacus porttitor. Curabitur viverra egestas elit, vel cursus orci semper vitae. Nam consequat malesuada lectus, nec congue neque rutrum ac. Nulla rutrum eleifend eleifend. Morbi commodo bibendum tellus, at mattis augue sodales ac.</p>

<p>Pellentesque vel tortor vitae tellus ultrices accumsan ut eu diam. Phasellus porta urna ac eros dapibus et porttitor risus pellentesque. Nullam fermentum volutpat tristique. Cras rutrum lacus vel magna pellentesque porttitor. Vivamus dignissim condimentum tellus, a sollicitudin magna aliquet sed. In hac habitasse platea dictumst. Ut blandit mattis mi id tristique. Quisque at ante ac enim dapibus vulputate vitae vitae est. Cras condimentum nulla sed risus tempor ut pretium sem lacinia. Nunc sit amet velit non odio adipiscing tincidunt ac vitae ante. Quisque nisl orci, commodo vitae venenatis nec, ornare scelerisque tortor. Nulla sagittis sagittis purus, sed rhoncus massa tempus vel. Duis arcu ligula, bibendum vitae consectetur vehicula, consequat vitae lectus.</p>

<p>Suspendisse et cursus neque. Pellentesque interdum, eros sed molestie consectetur, massa nibh vestibulum ante, et pellentesque tellus mi eget nisl. Morbi ultrices luctus pharetra. Maecenas ut quam tortor. Mauris ut tellus dui. Sed at nulla turpis, pretium vestibulum enim. Phasellus ornare, odio ut posuere mollis, felis diam molestie neque, non aliquam mauris leo at massa. Proin arcu nisi, condimentum non vulputate vitae, venenatis et metus. Nullam congue dui at nibh laoreet nec porta orci fermentum.</p>

<div id="chatbox" class="popupchat opened"></div>
</body>
<script>

$('.popupchat').popupchat({
    csrf:'<?= $_SESSION['popupchat_csrf'] ?>',
    me: { id:<?= $_GET['me'] ?>, nick:'DasBinIch', chat:'Me' },
    them: { id:<?= $_GET['them'] ?>, nick:'DerAndere', chat:'Theother' },
});

</script>
</html>
