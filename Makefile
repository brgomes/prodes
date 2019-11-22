help-default help:
	@echo "======================================================================"
	@echo " OPÇÕES DO MAKEFILE"
	@echo "======================================================================"
	@echo "     dump: Executa o dump no banco de dados"
	@echo "  restore: Executa o restore no banco de dados"
	@echo "     push: Executa o push do branch atual para os repositórios remotos"
	@echo "     pull: Executa o pull do repositório remoto para o branch atual"
	@echo "   commit: Adiciona os arquivos no índice e executa o commit"
	@echo ""

dump:
	mysqldump -h 45.79.92.163 -u root -pRoot-1982 -B --add-drop-database prodes --skip-lock-tables | pv -s 1M > database.sql
	make restore

restore:
	pv database.sql | mysql -uroot -proot

push:
	git push origin $(shell git rev-parse --abbrev-ref HEAD)

pull:
	git pull origin $(shell git rev-parse --abbrev-ref HEAD)

commit:
	git add .
	git commit -m "$(m)"
